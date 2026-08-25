
require_once __DIR__ . '/partials.php';
require_once __DIR__ . '/api/duffel.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$offerId = trim($_GET['offer_id'] ?? $_POST['offer_id'] ?? '');
$error = '';
$offer = [];
$passengers = [];

if ($offerId === '') {
    $error = 'Missing offer ID.';
} else {
    $api = mt_duffel_get_offer($offerId, true);

    if (!$api['ok']) {
        $error = $api['error'];
        error_log('PASSENGER PAGE DUFFEL | HTTP '.$api['status'].' | '.json_encode($api['data']));
    } else {
        $offer = $api['data']['data'] ?? [];
        $passengers = $offer['passengers'] ?? [];
        if (!is_array($passengers)) $passengers = [];
    }
}

if (empty($_SESSION['flight_csrf'])) {
    $_SESSION['flight_csrf'] = bin2hex(random_bytes(24));
}

function fp_type_label(array $passenger, int $index): string
{
    $type = strtolower((string)($passenger['type'] ?? ''));
    return match ($type) {
        'adult' => 'Adult',
        'child' => 'Child',
        'infant_without_seat', 'infant' => 'Infant',
        default => 'Passenger '.($index + 1)
    };
}

function fp_airline(array $offer): string
{
    if (!empty($offer['owner']['name'])) return (string)$offer['owner']['name'];

    foreach (($offer['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $segment) {
            if (!empty($segment['marketing_carrier']['name'])) {
                return (string)$segment['marketing_carrier']['name'];
            }
        }
    }

    return 'Airline';
}

function fp_time(?string $v): string
{
    $t = $v ? strtotime($v) : false;
    return $t ? date('H:i', $t) : '';
}

function fp_date(?string $v): string
{
    $t = $v ? strtotime($v) : false;
    return $t ? date('D, d M Y', $t) : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {

    $csrf = (string)($_POST['csrf'] ?? '');

    if (!hash_equals($_SESSION['flight_csrf'] ?? '', $csrf)) {
        $error = 'Your session expired. Please reload the page and try again.';
    } else {

        $submitted = $_POST['passenger'] ?? [];
        if (!is_array($submitted)) $submitted = [];

        $cleanPassengers = [];

        foreach ($passengers as $i => $apiPassenger) {

            $row = $submitted[$i] ?? [];

            $title = trim((string)($row['title'] ?? ''));
            $givenName = trim((string)($row['given_name'] ?? ''));
            $familyName = trim((string)($row['family_name'] ?? ''));
            $bornOn = trim((string)($row['born_on'] ?? ''));
            $gender = trim((string)($row['gender'] ?? ''));
            $email = trim((string)($row['email'] ?? ''));
            $phone = trim((string)($row['phone_number'] ?? ''));
            $nationality = strtoupper(trim((string)($row['nationality'] ?? '')));
            $passportNumber = strtoupper(trim((string)($row['passport_number'] ?? '')));
            $passportExpiry = trim((string)($row['passport_expiry'] ?? ''));

            if ($givenName === '' || $familyName === '' || $bornOn === '' || $gender === '') {
                $error = 'Please complete all required fields for '.fp_type_label($apiPassenger, $i).'.';
                break;
            }

            $dob = DateTime::createFromFormat('Y-m-d', $bornOn);
            if (!$dob || $dob->format('Y-m-d') !== $bornOn || $bornOn > date('Y-m-d')) {
                $error = 'Please enter a valid date of birth for '.fp_type_label($apiPassenger, $i).'.';
                break;
            }

            if ($i === 0) {
                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid contact email for the lead passenger.';
                    break;
                }
                if ($phone === '') {
                    $error = 'Please enter a contact phone number for the lead passenger.';
                    break;
                }
            }

            if ($passportExpiry !== '') {
                $exp = DateTime::createFromFormat('Y-m-d', $passportExpiry);
                if (!$exp || $exp->format('Y-m-d') !== $passportExpiry) {
                    $error = 'Please enter a valid passport expiry date.';
                    break;
                }
            }

            $cleanPassengers[] = [
                'id' => (string)($apiPassenger['id'] ?? ''),
                'type' => (string)($apiPassenger['type'] ?? ''),
                'title' => $title,
                'given_name' => $givenName,
                'family_name' => $familyName,
                'born_on' => $bornOn,
                'gender' => $gender,
                'email' => $email,
                'phone_number' => $phone,
                'nationality' => $nationality,
                'passport_number' => $passportNumber,
                'passport_expiry' => $passportExpiry
            ];
        }

        if ($error === '') {
            $_SESSION['flight_checkout'][$offerId] = [
                'offer_id' => $offerId,
                'passengers' => $cleanPassengers,
                'saved_at' => time()
            ];

            header('Location: flight-review.php?offer_id='.rawurlencode($offerId));
            exit;
        }
    }
}

site_header('Passenger Details');
?>

<style>
.fp-page{background:#f4f8fb;min-height:760px;padding:34px 0 80px}
.fp-wrap{width:min(1180px,calc(100% - 32px));margin:auto}
.fp-head{margin-bottom:18px}.fp-head h1{margin:0;color:#10253d;font:800 30px Manrope,Inter,sans-serif}.fp-head p{margin:6px 0 0;color:#71869a}
.fp-grid{display:grid;grid-template-columns:minmax(0,1fr) 330px;gap:18px;align-items:start}
.fp-card,.fp-summary{background:#fff;border:1px solid #dce6ef;border-radius:16px;box-shadow:0 8px 26px rgba(8,47,95,.04)}
.fp-card{padding:20px;margin-bottom:14px}.fp-card h2{margin:0 0 14px;font-size:17px;color:#10253d}
.fp-passenger-title{display:flex;justify-content:space-between;align-items:center;margin-bottom:15px}.fp-passenger-title strong{font-size:16px;color:#10253d}.fp-type{background:#e7f4ff;color:#0877bd;border-radius:30px;padding:6px 9px;font-size:9px;font-weight:900}
.fp-fields{display:grid;grid-template-columns:120px 1fr 1fr;gap:11px}.fp-fields.two{grid-template-columns:1fr 1fr}.fp-field label{display:block;font-size:10px;color:#6f8497;font-weight:900;margin-bottom:5px}
.fp-field input,.fp-field select{width:100%;height:43px;border:1px solid #d4e0ea;border-radius:9px;padding:0 11px;background:#fff;color:#10253d;outline:0}.fp-field input:focus,.fp-field select:focus{border-color:#1394e5;box-shadow:0 0 0 3px rgba(19,148,229,.08)}
.fp-note{font-size:10px;color:#7e91a4;margin-top:8px}
.fp-summary{padding:18px;position:sticky;top:90px}.fp-summary h3{margin:0 0 14px;color:#10253d}.fp-airline{font-weight:900;color:#10253d;margin-bottom:12px}
.fp-slice{padding:11px 0;border-top:1px solid #edf2f6}.fp-slice:first-of-type{border-top:0}.fp-route{display:flex;justify-content:space-between;gap:10px;align-items:center}.fp-route strong{font-size:14px}.fp-route small{display:block;color:#8194a6;font-size:9px;margin-top:3px}.fp-arrow{color:#8ba0b3}
.fp-price{border-top:1px solid #edf2f6;margin-top:10px;padding-top:14px;display:flex;justify-content:space-between;align-items:end}.fp-price span{font-size:10px;color:#8092a3}.fp-price strong{font:900 24px Manrope,Inter,sans-serif;color:#082f5f}
.fp-submit{width:100%;border:0;background:#082f5f;color:#fff;border-radius:10px;padding:13px 16px;font-weight:900;cursor:pointer;margin-top:14px}.fp-submit:hover{filter:brightness(1.05)}
.fp-back{display:block;text-align:center;margin-top:10px;color:#51708a;font-size:10px;text-decoration:none;font-weight:800}
.fp-error{background:#fff0f2;border:1px solid #ffd0d7;color:#b52d43;padding:14px 16px;border-radius:11px;margin-bottom:16px}
.fp-secure{background:#eff8f4;color:#167354;border-radius:10px;padding:10px 11px;font-size:9px;margin-top:12px}
@media(max-width:850px){.fp-grid{grid-template-columns:1fr}.fp-summary{position:static}.fp-fields,.fp-fields.two{grid-template-columns:1fr}}
</style>

<section class="fp-page">
<div class="fp-wrap">

    <div class="fp-head">
        <h1>Passenger details</h1>
        <p>Enter passenger names exactly as shown on their travel documents.</p>
    </div>

    <?php if($error): ?>
        <div class="fp-error"><strong>Passenger details error:</strong> <?=h($error)?></div>
    <?php endif; ?>

    <?php if(!$error || $offer): ?>
    <form method="post" action="flight-passengers.php?offer_id=<?=urlencode($offerId)?>">
        <input type="hidden" name="offer_id" value="<?=h($offerId)?>">
        <input type="hidden" name="csrf" value="<?=h($_SESSION['flight_csrf'])?>">

        <div class="fp-grid">

            <div>
                <?php if(!$passengers): ?>
                    <div class="fp-card">
                        <h2>No passenger records returned</h2>
                        <p>Please return to the flight search and select another offer.</p>
                    </div>
                <?php endif; ?>

                <?php foreach($passengers as $i => $apiPassenger):
                    $old = $_POST['passenger'][$i] ?? [];
                    $label = fp_type_label($apiPassenger,$i);
                ?>
                <div class="fp-card">

                    <div class="fp-passenger-title">
                        <strong>Passenger <?=$i+1?></strong>
                        <span class="fp-type"><?=h($label)?></span>
                    </div>

                    <div class="fp-fields">
                        <div class="fp-field">
                            <label>TITLE *</label>
                            <select name="passenger[<?=$i?>][title]" required>
                                <option value="">Select</option>
                                <?php foreach(['mr'=>'Mr','mrs'=>'Mrs','ms'=>'Ms','miss'=>'Miss','dr'=>'Dr'] as $v=>$text): ?>
                                    <option value="<?=$v?>" <?=($old['title']??'')===$v?'selected':''?>><?=$text?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="fp-field">
                            <label>FIRST / GIVEN NAME *</label>
                            <input type="text" name="passenger[<?=$i?>][given_name]" value="<?=h((string)($old['given_name']??''))?>" required autocomplete="given-name">
                        </div>

                        <div class="fp-field">
                            <label>LAST / FAMILY NAME *</label>
                            <input type="text" name="passenger[<?=$i?>][family_name]" value="<?=h((string)($old['family_name']??''))?>" required autocomplete="family-name">
                        </div>
                    </div>

                    <div class="fp-fields two" style="margin-top:11px">
                        <div class="fp-field">
                            <label>DATE OF BIRTH *</label>
                            <input type="date" name="passenger[<?=$i?>][born_on]" value="<?=h((string)($old['born_on']??''))?>" max="<?=date('Y-m-d')?>" required>
                        </div>

                        <div class="fp-field">
                            <label>GENDER *</label>
                            <select name="passenger[<?=$i?>][gender]" required>
                                <option value="">Select</option>
                                <option value="m" <?=($old['gender']??'')==='m'?'selected':''?>>Male</option>
                                <option value="f" <?=($old['gender']??'')==='f'?'selected':''?>>Female</option>
                            </select>
                        </div>
                    </div>

                    <?php if($i===0): ?>
                    <div class="fp-fields two" style="margin-top:11px">
                        <div class="fp-field">
                            <label>CONTACT EMAIL *</label>
                            <input type="email" name="passenger[<?=$i?>][email]" value="<?=h((string)($old['email']??''))?>" required autocomplete="email">
                        </div>

                        <div class="fp-field">
                            <label>CONTACT PHONE *</label>
                            <input type="tel" name="passenger[<?=$i?>][phone_number]" value="<?=h((string)($old['phone_number']??''))?>" placeholder="+34..." required autocomplete="tel">
                        </div>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="passenger[<?=$i?>][email]" value="">
                        <input type="hidden" name="passenger[<?=$i?>][phone_number]" value="">
                    <?php endif; ?>

                    <div class="fp-fields" style="margin-top:11px;grid-template-columns:1fr 1fr 1fr">
                        <div class="fp-field">
                            <label>NATIONALITY</label>
                            <input type="text" name="passenger[<?=$i?>][nationality]" maxlength="2" placeholder="PK / ES" value="<?=h((string)($old['nationality']??''))?>">
                        </div>

                        <div class="fp-field">
                            <label>PASSPORT NUMBER</label>
                            <input type="text" name="passenger[<?=$i?>][passport_number]" value="<?=h((string)($old['passport_number']??''))?>">
                        </div>

                        <div class="fp-field">
                            <label>PASSPORT EXPIRY</label>
                            <input type="date" name="passenger[<?=$i?>][passport_expiry]" value="<?=h((string)($old['passport_expiry']??''))?>">
                        </div>
                    </div>

                    <div class="fp-note">Passport details are collected now so the booking workflow can support airlines/routes that require travel document data.</div>

                </div>
                <?php endforeach; ?>
            </div>

            <aside class="fp-summary">
                <h3>Your flight</h3>
                <div class="fp-airline"><?=h(fp_airline($offer))?></div>

                <?php foreach(($offer['slices'] ?? []) as $slice):
                    $segments = $slice['segments'] ?? [];
                    $first = $segments[0] ?? [];
                    $last = $segments ? $segments[count($segments)-1] : [];
                ?>
                <div class="fp-slice">
                    <div class="fp-route">
                        <div>
                            <strong><?=h((string)($first['origin']['iata_code'] ?? ''))?> <?=h(fp_time($first['departing_at'] ?? null))?></strong>
                            <small><?=h(fp_date($first['departing_at'] ?? null))?></small>
                        </div>
                        <span class="fp-arrow">→</span>
                        <div style="text-align:right">
                            <strong><?=h((string)($last['destination']['iata_code'] ?? ''))?> <?=h(fp_time($last['arriving_at'] ?? null))?></strong>
                            <small><?=h(fp_date($last['arriving_at'] ?? null))?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="fp-price">
                    <span>Total fare</span>
                    <strong><?=h((string)($offer['total_currency'] ?? 'EUR'))?> <?=number_format((float)($offer['total_amount'] ?? 0),2)?></strong>
                </div>

                <?php if($passengers): ?>
                    <button class="fp-submit" type="submit">Review booking</button>
                <?php endif; ?>

                <a class="fp-back" href="flight-fare.php?offer_id=<?=urlencode($offerId)?>">← Back to fare</a>

                <div class="fp-secure">Passenger data is kept server-side in the current booking session and is not placed in the URL.</div>
            </aside>

        </div>
    </form>
    <?php endif; ?>

</div>
</section>

<?php site_footer(); ?>
