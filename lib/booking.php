<?php
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../api/duffel.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

function mt_server_supabase_key(): string
{
    return getenv('SUPABASE_SECRET_KEY') ?: '';
}

function mt_booking_sb_request(string $path, string $method = 'GET', ?array $payload = null, array $headers = []): array
{
    $key = mt_server_supabase_key();
    if ($key === '') return ['ok'=>false,'status'=>0,'data'=>[],'raw'=>'','error'=>'SUPABASE_SECRET_KEY missing'];

    $ch = curl_init(rtrim(SUPABASE_URL, '/') . '/rest/v1/' . $path);
    $baseHeaders = [
        'apikey: ' . $key,
        'Authorization: Bearer ' . $key,
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => array_merge($baseHeaders, $headers),
        CURLOPT_TIMEOUT => 30
    ]);
    if ($payload !== null) curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
    $raw = curl_exec($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    $data = json_decode((string)$raw, true);
    return ['ok'=>$status>=200 && $status<300,'status'=>$status,'data'=>is_array($data)?$data:[],'raw'=>(string)$raw,'error'=>$err];
}

function mt_booking_insert(array $row): array
{
    return mt_booking_sb_request('flight_bookings', 'POST', $row, ['Prefer: return=representation']);
}

function mt_booking_find_by_ref(string $ref): ?array
{
    $q = 'flight_bookings?booking_ref=eq.' . rawurlencode($ref) . '&select=*&limit=1';
    $r = mt_booking_sb_request($q);
    return $r['ok'] && !empty($r['data'][0]) ? $r['data'][0] : null;
}

function mt_booking_find_by_pi(string $pi): ?array
{
    $q = 'flight_bookings?payment_intent_id=eq.' . rawurlencode($pi) . '&select=*&limit=1';
    $r = mt_booking_sb_request($q);
    return $r['ok'] && !empty($r['data'][0]) ? $r['data'][0] : null;
}

function mt_booking_update(string $ref, array $row): bool
{
    $row['updated_at'] = gmdate('c');
    $r = mt_booking_sb_request('flight_bookings?booking_ref=eq.' . rawurlencode($ref), 'PATCH', $row, ['Prefer: return=minimal']);
    return $r['ok'];
}

function mt_booking_ref(): string
{
    return 'MT-' . gmdate('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

function mt_reprice_checkout(string $offerId, array $checkout): array
{
    $api = mt_duffel_get_offer($offerId, true);
    if (!$api['ok']) return ['ok'=>false,'error'=>$api['error'] ?: 'Unable to refresh fare.'];
    $offer = $api['data']['data'] ?? [];
    $base = (float)($offer['total_amount'] ?? 0);
    $currency = strtoupper((string)($offer['total_currency'] ?? 'EUR'));
    $selected = $checkout['selected_services'] ?? [];
    $services = [];
    $extra = 0.0;
    $allowed = [];
    foreach (($offer['available_services'] ?? []) as $s) {
        if (($s['type'] ?? '') !== 'baggage' || empty($s['id'])) continue;
        $allowed[(string)$s['id']] = $s;
    }
    foreach ($selected as $sid => $qty) {
        if (empty($allowed[$sid])) continue;
        $qty = max(0, min((int)$qty, (int)($allowed[$sid]['maximum_quantity'] ?? 1)));
        if ($qty < 1) continue;
        $services[] = ['id'=>(string)$sid, 'quantity'=>$qty];
        $extra += $qty * (float)($allowed[$sid]['total_amount'] ?? 0);
    }
    return [
        'ok'=>true,
        'offer'=>$offer,
        'amount'=>round($base + $extra, 2),
        'currency'=>$currency,
        'services'=>$services
    ];
}

function mt_duffel_order_passengers(array $checkout): array
{
    $out = [];
    foreach (($checkout['passengers'] ?? []) as $p) {
        $row = [
            'id'=>(string)($p['id'] ?? ''),
            'title'=>(string)($p['title'] ?? ''),
            'given_name'=>(string)($p['given_name'] ?? ''),
            'family_name'=>(string)($p['family_name'] ?? ''),
            'born_on'=>(string)($p['born_on'] ?? ''),
            'gender'=>(string)($p['gender'] ?? '')
        ];
        if (!empty($p['email'])) $row['email'] = (string)$p['email'];
        if (!empty($p['phone_number'])) $row['phone_number'] = (string)$p['phone_number'];
        if (!empty($p['passport_number']) && !empty($p['passport_expiry']) && !empty($p['nationality'])) {
            $row['identity_documents'] = [[
                'type'=>'passport',
                'unique_identifier'=>(string)$p['passport_number'],
                'expires_on'=>(string)$p['passport_expiry'],
                'issuing_country_code'=>(string)$p['nationality']
            ]];
        }
        $out[] = $row;
    }
    return $out;
}

function mt_create_duffel_order(array $booking): array
{
    $checkout = is_array($booking['checkout_json'] ?? null) ? $booking['checkout_json'] : json_decode((string)($booking['checkout_json'] ?? '{}'), true);
    if (!is_array($checkout)) return ['ok'=>false,'error'=>'Stored booking data is invalid.'];

    $offerId = (string)$booking['offer_id'];
    $reprice = mt_reprice_checkout($offerId, $checkout);
    if (!$reprice['ok']) return $reprice;

    $paid = (float)$booking['amount'];
    if (abs($paid - (float)$reprice['amount']) > 0.009 || strtoupper((string)$booking['currency']) !== $reprice['currency']) {
        return ['ok'=>false,'error'=>'Fare changed after payment. Manual review/refund required.'];
    }

    $data = [
        'selected_offers'=>[$offerId],
        'passengers'=>mt_duffel_order_passengers($checkout),
        'payments'=>[[
            'type'=>'balance',
            'currency'=>$reprice['currency'],
            'amount'=>number_format((float)$reprice['amount'], 2, '.', '')
        ]]
    ];
    if (!empty($reprice['services'])) $data['services'] = $reprice['services'];

    $api = mt_duffel_request('/air/orders', 'POST', ['data'=>$data], [
        'Idempotency-Key: stripe-' . (string)$booking['payment_intent_id']
    ]);
    if (!$api['ok']) return ['ok'=>false,'error'=>$api['error'] ?: 'Duffel order failed.','api'=>$api];

    $order = $api['data']['data'] ?? [];
    return [
        'ok'=>true,
        'order'=>$order,
        'order_id'=>(string)($order['id'] ?? ''),
        'pnr'=>(string)($order['booking_reference'] ?? '')
    ];
}

function mt_pdf_bytes(array $booking, string $kind, ?array $order = null): string
{
    $checkout = is_array($booking['checkout_json'] ?? null) ? $booking['checkout_json'] : json_decode((string)($booking['checkout_json'] ?? '{}'), true);
    $checkout = is_array($checkout) ? $checkout : [];
    $passengers = $checkout['passengers'] ?? [];
    $title = $kind === 'confirmed' ? 'Booking Confirmed' : 'Payment Received – Booking Processing';
    $pnr = $order['booking_reference'] ?? ($booking['pnr'] ?? '');
    $rows = '';
    foreach ($passengers as $p) {
        $name = htmlspecialchars(trim(($p['given_name'] ?? '').' '.($p['family_name'] ?? '')), ENT_QUOTES, 'UTF-8');
        $rows .= '<tr><td style="padding:7px;border-bottom:1px solid #e5e7eb">'.$name.'</td><td style="padding:7px;border-bottom:1px solid #e5e7eb">'.htmlspecialchars((string)($p['type'] ?? 'Passenger')).'</td></tr>';
    }
    $statusText = $kind === 'confirmed'
        ? 'Your airline booking has been confirmed. Please check all details carefully.'
        : 'We have received your payment. Your airline booking is now being processed. This document is not an airline ticket or final PNR confirmation.';
    $html = '<html><body style="font-family:DejaVu Sans,Arial;color:#12263b;padding:24px">'
        .'<div style="background:#062b55;color:white;padding:20px"><h1 style="margin:0;font-size:22px">Mustafa Travels & Tours</h1><div style="margin-top:6px">'.$title.'</div></div>'
        .'<div style="padding:18px;border:1px solid #dbe4ec"><p>'.$statusText.'</p>'
        .'<table width="100%" cellspacing="0" cellpadding="0" style="margin-top:15px">'
        .'<tr><td><b>Reference</b></td><td>'.htmlspecialchars((string)$booking['booking_ref']).'</td></tr>'
        .'<tr><td><b>Amount</b></td><td>'.htmlspecialchars(strtoupper((string)$booking['currency'])).' '.number_format((float)$booking['amount'],2).'</td></tr>'
        .($pnr ? '<tr><td><b>PNR</b></td><td style="font-size:18px;font-weight:bold">'.htmlspecialchars((string)$pnr).'</td></tr>' : '')
        .'</table><h3 style="margin-top:22px">Passengers</h3><table width="100%" cellspacing="0">'.$rows.'</table>'
        .'<p style="margin-top:25px;font-size:11px;color:#64748b">'.htmlspecialchars(ADDRESS).' · '.htmlspecialchars(EMAIL).' · '.htmlspecialchars(PHONE1).'</p>'
        .'</div></body></html>';
    $dompdf = new Dompdf(['isRemoteEnabled'=>false]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4');
    $dompdf->render();
    return $dompdf->output();
}

function mt_send_mail_with_pdf(string $to, string $subject, string $html, string $filename, string $pdfBytes): array
{
    $host = getenv('SMTP_HOST') ?: '';
    $user = getenv('SMTP_USERNAME') ?: '';
    $pass = getenv('SMTP_PASSWORD') ?: '';
    $port = (int)(getenv('SMTP_PORT') ?: 587);
    $from = getenv('SMTP_FROM_EMAIL') ?: EMAIL;
    $fromName = getenv('SMTP_FROM_NAME') ?: SITE_NAME;
    if ($host === '' || $user === '' || $pass === '') return ['ok'=>false,'error'=>'SMTP is not configured.'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->Port = $port;
        $mail->SMTPSecure = $port === 465 ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom($from, $fromName);
        $mail->addAddress($to);
        $mail->addReplyTo(EMAIL, SITE_NAME);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $html;
        $mail->AltBody = strip_tags(str_replace(['<br>','<br/>','<br />'], "\n", $html));
        $mail->addStringAttachment($pdfBytes, $filename, 'base64', 'application/pdf');
        $mail->send();
        return ['ok'=>true,'error'=>''];
    } catch (MailException $e) {
        error_log('BOOKING EMAIL FAILED: '.$mail->ErrorInfo);
        return ['ok'=>false,'error'=>$mail->ErrorInfo ?: $e->getMessage()];
    }
}

function mt_send_payment_receipt(array $booking): bool
{
    if (!empty($booking['receipt_email_sent_at'])) return true;
    $pdf = mt_pdf_bytes($booking, 'receipt');
    $ref = (string)$booking['booking_ref'];
    $html = '<p>Dear customer,</p><p>We have received your payment for booking reference <b>'.htmlspecialchars($ref).'</b>.</p><p>Your airline booking is now being processed. The attached PDF is a payment/booking request receipt and is <b>not</b> the final airline ticket.</p><p>Regards,<br>Mustafa Travels & Tours</p>';
    $r = mt_send_mail_with_pdf((string)$booking['customer_email'], 'Payment received – '.$ref, $html, $ref.'-payment-received.pdf', $pdf);
    if ($r['ok']) mt_booking_update($ref, ['receipt_email_sent_at'=>gmdate('c')]);
    return $r['ok'];
}

function mt_send_confirmation(array $booking, array $order): bool
{
    if (!empty($booking['confirmation_email_sent_at'])) return true;
    $ref = (string)$booking['booking_ref'];
    $pnr = (string)($order['booking_reference'] ?? $booking['pnr'] ?? '');
    $pdf = mt_pdf_bytes($booking, 'confirmed', $order);
    $html = '<p>Dear customer,</p><p>Your airline booking has been confirmed.</p><p><b>Booking reference:</b> '.htmlspecialchars($ref).'<br><b>PNR:</b> '.htmlspecialchars($pnr).'</p><p>Your confirmation PDF is attached.</p><p>Regards,<br>Mustafa Travels & Tours</p>';
    $r = mt_send_mail_with_pdf((string)$booking['customer_email'], 'Booking confirmed – PNR '.$pnr, $html, $ref.'-booking-confirmed.pdf', $pdf);
    if ($r['ok']) mt_booking_update($ref, ['confirmation_email_sent_at'=>gmdate('c')]);
    return $r['ok'];
}

function mt_process_paid_booking(array $booking): array
{
    $ref = (string)$booking['booking_ref'];
    mt_booking_update($ref, ['status'=>'payment_received']);
    $fresh = mt_booking_find_by_ref($ref) ?: $booking;
    mt_send_payment_receipt($fresh);

    if (!empty($fresh['duffel_order_id'])) {
        return ['ok'=>true,'booking'=>$fresh,'already_booked'=>true];
    }

    mt_booking_update($ref, ['status'=>'booking_processing','error_message'=>null]);
    $result = mt_create_duffel_order($fresh);
    if (!$result['ok']) {
        mt_booking_update($ref, ['status'=>'booking_failed','error_message'=>(string)$result['error']]);
        return ['ok'=>false,'error'=>$result['error'],'booking'=>mt_booking_find_by_ref($ref) ?: $fresh];
    }

    mt_booking_update($ref, [
        'status'=>'confirmed',
        'duffel_order_id'=>$result['order_id'],
        'pnr'=>$result['pnr'],
        'error_message'=>null
    ]);
    $confirmed = mt_booking_find_by_ref($ref) ?: $fresh;
    mt_send_confirmation($confirmed, $result['order']);
    return ['ok'=>true,'booking'=>mt_booking_find_by_ref($ref) ?: $confirmed,'order'=>$result['order']];
}
