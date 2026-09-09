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

function mt_booking_find_for_customer(string $ref, string $email): ?array
{
    $q = 'flight_bookings?booking_ref=eq.' . rawurlencode($ref) . '&customer_email=ilike.' . rawurlencode($email) . '&select=*&limit=1';
    $r = mt_booking_sb_request($q);
    return $r['ok'] && !empty($r['data'][0]) ? $r['data'][0] : null;
}

function mt_booking_list(int $limit = 100): array
{
    $limit = max(1, min(250, $limit));
    $r = mt_booking_sb_request('flight_bookings?select=*&order=created_at.desc&limit='.$limit);
    return $r['ok'] && is_array($r['data']) ? $r['data'] : [];
}

function mt_booking_order(array $booking): array
{
    $id = trim((string)($booking['duffel_order_id'] ?? ''));
    if ($id === '') return [];
    $r = mt_duffel_request('/air/orders/' . rawurlencode($id), 'GET');
    return $r['ok'] ? (array)($r['data']['data'] ?? []) : [];
}

function mt_booking_checkout(array $booking): array
{
    $v = $booking['checkout_json'] ?? [];
    if (is_array($v)) return $v;
    $d = json_decode((string)$v, true);
    return is_array($d) ? $d : [];
}

function mt_booking_itinerary(array $order): array
{
    $rows = [];
    foreach (($order['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $seg) {
            $dep = $seg['departing_at'] ?? '';
            $arr = $seg['arriving_at'] ?? '';
            $carrier = $seg['marketing_carrier']['name'] ?? $seg['operating_carrier']['name'] ?? '';
            $code = $seg['marketing_carrier']['iata_code'] ?? $seg['operating_carrier']['iata_code'] ?? '';
            $num = $seg['marketing_carrier_flight_number'] ?? $seg['operating_carrier_flight_number'] ?? '';
            $rows[] = [
                'airline'=>(string)$carrier,
                'flight'=>trim((string)$code.' '.(string)$num),
                'origin'=>(string)($seg['origin']['iata_code'] ?? ''),
                'origin_name'=>(string)($seg['origin']['name'] ?? ''),
                'destination'=>(string)($seg['destination']['iata_code'] ?? ''),
                'destination_name'=>(string)($seg['destination']['name'] ?? ''),
                'departing_at'=>(string)$dep,
                'arriving_at'=>(string)$arr,
            ];
        }
    }
    return $rows;
}

function mt_booking_baggage(array $order): array
{
    $out = [];
    foreach (($order['slices'] ?? []) as $slice) foreach (($slice['segments'] ?? []) as $seg) foreach (($seg['passengers'] ?? []) as $sp) {
        foreach (($sp['baggages'] ?? []) as $b) {
            $type = (string)($b['type'] ?? 'baggage');
            $qty = (int)($b['quantity'] ?? 0);
            $text = ucfirst(str_replace('_',' ', $type)) . ($qty ? ': '.$qty.' piece'.($qty===1?'':'s') : '');
            if (!empty($b['weight'])) $text .= ' × '.$b['weight'].' '.strtoupper((string)($b['weight_unit'] ?? 'kg'));
            $out[] = $text;
        }
    }
    foreach (($order['services'] ?? []) as $svc) {
        if (($svc['type'] ?? '') !== 'baggage') continue;
        $text = 'Extra checked baggage';
        if (!empty($svc['quantity'])) $text .= ': '.$svc['quantity'].' piece'.((int)$svc['quantity']===1?'':'s');
        if (!empty($svc['metadata']['maximum_weight_kg'])) $text .= ' × '.$svc['metadata']['maximum_weight_kg'].' KG';
        $out[] = $text;
    }
    return array_values(array_unique(array_filter($out)));
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
    $checkout = mt_booking_checkout($booking);
    $order = is_array($order) && $order ? $order : mt_booking_order($booking);
    $passengers = $checkout['passengers'] ?? [];
    $confirmed = $kind === 'confirmed';
    $title = $confirmed ? 'Booking Confirmed' : 'Payment Received - Booking Processing';
    $pnr = (string)($order['booking_reference'] ?? $booking['pnr'] ?? '');
    $e = fn($v)=>htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');

    $paxRows='';
    foreach ($passengers as $p) {
        $name=trim(($p['title']??'').' '.($p['given_name']??'').' '.($p['family_name']??''));
        $dob=(string)($p['born_on']??'');
        $doc=(string)($p['passport_number']??'');
        $paxRows.='<tr><td>'.$e($name).'</td><td>'.$e(ucfirst((string)($p['type']??'passenger'))).'</td><td>'.$e($dob).'</td><td>'.$e($doc).'</td></tr>';
    }
    if ($paxRows==='') $paxRows='<tr><td colspan="4">Passenger details unavailable</td></tr>';

    $itinRows='';
    foreach (mt_booking_itinerary($order) as $seg) {
        $dep=$seg['departing_at'] ? date('d M Y H:i', strtotime($seg['departing_at'])) : '';
        $arr=$seg['arriving_at'] ? date('d M Y H:i', strtotime($seg['arriving_at'])) : '';
        $itinRows.='<tr><td><b>'.$e($seg['airline']).'</b><br>'.$e($seg['flight']).'</td><td><b>'.$e($seg['origin']).'</b><br>'.$e($seg['origin_name']).'</td><td>'.$e($dep).'</td><td><b>'.$e($seg['destination']).'</b><br>'.$e($seg['destination_name']).'</td><td>'.$e($arr).'</td></tr>';
    }
    if ($itinRows==='') $itinRows='<tr><td colspan="5">Full airline itinerary becomes available after confirmation.</td></tr>';

    $bags=mt_booking_baggage($order);
    $bagHtml=$bags ? '<ul><li>'.implode('</li><li>',array_map($e,$bags)).'</li></ul>' : '<p>Please check the airline fare conditions for baggage allowance.</p>';
    $statusText=$confirmed ? 'Your airline booking has been confirmed. Please check names, dates, baggage and flight details carefully.' : 'We have received your payment. Your airline booking is now being processed. This document is not an airline ticket or final PNR confirmation.';

    $html='<!doctype html><html><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,Arial;color:#12263b;font-size:11px} .head{background:#062b55;color:#fff;padding:18px}.head h1{margin:0;font-size:22px}.box{border:1px solid #dbe4ec;padding:16px}.meta{width:100%;margin:10px 0 16px}.meta td{padding:4px}.pnr{font-size:20px;font-weight:bold;color:#087a45}h3{color:#062b55;margin:18px 0 7px}table.grid{width:100%;border-collapse:collapse}table.grid th{background:#eef5fb;text-align:left;padding:7px;font-size:9px}table.grid td{padding:7px;border-bottom:1px solid #e5e7eb;vertical-align:top}.foot{margin-top:22px;color:#64748b;font-size:9px}</style></head><body>'
      .'<div class="head"><h1>Mustafa Travels & Tours</h1><div>'.$e($title).'</div></div><div class="box"><p>'.$e($statusText).'</p>'
      .'<table class="meta"><tr><td><b>Mustafa Travels Ref.</b></td><td>'.$e($booking['booking_ref']??'').'</td><td><b>Status</b></td><td>'.$e(ucwords(str_replace('_',' ',(string)($booking['status']??'')))).'</td></tr>'
      .'<tr><td><b>Amount</b></td><td>'.$e(strtoupper((string)($booking['currency']??'EUR'))).' '.number_format((float)($booking['amount']??0),2).'</td><td><b>Airline PNR</b></td><td class="pnr">'.$e($pnr ?: 'Pending').'</td></tr></table>'
      .'<h3>Flight itinerary</h3><table class="grid"><tr><th>Airline / Flight</th><th>From</th><th>Departure</th><th>To</th><th>Arrival</th></tr>'.$itinRows.'</table>'
      .'<h3>Passengers</h3><table class="grid"><tr><th>Name</th><th>Type</th><th>Date of birth</th><th>Passport</th></tr>'.$paxRows.'</table>'
      .'<h3>Baggage allowance & extras</h3>'.$bagHtml
      .'<div class="foot">'.$e(ADDRESS).' · '.$e(EMAIL).' · '.$e(PHONE1).'<br>Please verify all travel documents and airline schedule before departure.</div></div></body></html>';
    $dompdf=new Dompdf(['isRemoteEnabled'=>false]); $dompdf->loadHtml($html,'UTF-8'); $dompdf->setPaper('A4'); $dompdf->render(); return $dompdf->output();
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
    $r = mt_send_mail_with_pdf((string)$booking['customer_email'], 'Payment received - '.$ref, $html, $ref.'-payment-received.pdf', $pdf);
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
    $r = mt_send_mail_with_pdf((string)$booking['customer_email'], 'Booking confirmed - PNR '.$pnr, $html, $ref.'-booking-confirmed.pdf', $pdf);
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
