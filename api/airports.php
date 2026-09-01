<?php
declare(strict_types=1);
require_once __DIR__ . '/duffel.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');

$q = trim((string)($_GET['q'] ?? ''));
if (mb_strlen($q) < 2) {
    echo json_encode(['ok' => true, 'data' => []], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Duffel Places Suggestions searches by airport/city/country name or IATA code.
$api = mt_duffel_request('/places/suggestions?query=' . rawurlencode($q), 'GET');
if (!$api['ok']) {
    http_response_code($api['status'] ?: 502);
    echo json_encode(['ok' => false, 'data' => [], 'error' => $api['error']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

$rows = [];
$seen = [];
$places = $api['data']['data'] ?? [];
if (!is_array($places)) $places = [];

$addAirport = function(array $a) use (&$rows, &$seen): void {
    $code = strtoupper(trim((string)($a['iata_code'] ?? '')));
    if (!preg_match('/^[A-Z]{3}$/', $code) || isset($seen[$code])) return;
    $seen[$code] = true;
    $city = trim((string)($a['city_name'] ?? ($a['city']['name'] ?? '')));
    $name = trim((string)($a['name'] ?? 'Airport'));
    $country = strtoupper(trim((string)($a['iata_country_code'] ?? '')));
    $rows[] = [
        'iata_code' => $code,
        'name' => $name,
        'city_name' => $city,
        'country_code' => $country,
        'label' => ($city !== '' ? $city : $name) . ' (' . $code . ')',
        'subtitle' => $name . ($country !== '' ? ' · ' . $country : '')
    ];
};

foreach ($places as $place) {
    if (!is_array($place)) continue;
    $type = (string)($place['type'] ?? '');
    if ($type === 'airport') {
        // Some responses return the airport directly, others wrap it in airports[].
        if (!empty($place['iata_code'])) $addAirport($place);
        foreach (($place['airports'] ?? []) as $a) if (is_array($a)) $addAirport($a);
    } elseif ($type === 'city') {
        foreach (($place['airports'] ?? []) as $a) if (is_array($a)) $addAirport($a);
    } else {
        if (!empty($place['iata_code'])) $addAirport($place);
        foreach (($place['airports'] ?? []) as $a) if (is_array($a)) $addAirport($a);
    }
}

// Exact IATA match first, then city/name alphabetically.
usort($rows, function(array $a, array $b) use ($q): int {
    $uq = strtoupper($q);
    $ae = $a['iata_code'] === $uq ? 0 : 1;
    $be = $b['iata_code'] === $uq ? 0 : 1;
    if ($ae !== $be) return $ae <=> $be;
    return strcasecmp($a['label'], $b['label']);
});

$rows = array_slice($rows, 0, 15);
echo json_encode(['ok' => true, 'data' => $rows], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
