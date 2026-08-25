<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Mustafa Travels - Duffel provider
|--------------------------------------------------------------------------
| Requires DUFFEL_API_KEY in Render Environment.
| Later Travelport can be added as a second provider without changing UI.
|--------------------------------------------------------------------------
*/

function mt_duffel_key(): string
{
    return getenv('DUFFEL_API_KEY') ?: '';
}

function mt_duffel_request(string $endpoint, string $method = 'GET', ?array $payload = null): array
{
    $key = mt_duffel_key();

    if ($key === '') {
        return [
            'ok' => false,
            'status' => 0,
            'data' => [],
            'error' => 'DUFFEL_API_KEY is missing from Render Environment.'
        ];
    }

    $ch = curl_init('https://api.duffel.com' . $endpoint);

    $headers = [
        'Authorization: Bearer ' . $key,
        'Accept: application/json',
        'Accept-Encoding: gzip',
        'Content-Type: application/json',
        'Duffel-Version: v2'
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_ENCODING => '',
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 55
    ]);

    if ($payload !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_SLASHES));
    }

    $raw = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($raw === false) {
        return [
            'ok' => false,
            'status' => $status,
            'data' => [],
            'error' => $curlError ?: 'Unable to connect to Duffel.'
        ];
    }

    $json = json_decode((string)$raw, true);
    if (!is_array($json)) {
        $json = [];
    }

    if ($status < 200 || $status >= 300) {
        $message = $json['errors'][0]['message']
            ?? $json['errors'][0]['title']
            ?? ('Duffel API returned HTTP ' . $status);

        return [
            'ok' => false,
            'status' => $status,
            'data' => $json,
            'error' => $message
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'data' => $json,
        'error' => ''
    ];
}

function mt_duffel_search(array $criteria): array
{
    $slices = [[
        'origin' => $criteria['origin'],
        'destination' => $criteria['destination'],
        'departure_date' => $criteria['departure']
    ]];

    if (($criteria['trip_type'] ?? 'round') === 'round' && !empty($criteria['return_date'])) {
        $slices[] = [
            'origin' => $criteria['destination'],
            'destination' => $criteria['origin'],
            'departure_date' => $criteria['return_date']
        ];
    }

    $passengers = [];

    for ($i = 0; $i < (int)$criteria['adults']; $i++) {
        $passengers[] = ['age' => 30];
    }
    for ($i = 0; $i < (int)$criteria['children']; $i++) {
        $passengers[] = ['age' => 8];
    }
    for ($i = 0; $i < (int)$criteria['infants']; $i++) {
        $passengers[] = ['age' => 1];
    }

    return mt_duffel_request(
        '/air/offer_requests?return_offers=true&supplier_timeout=20000',
        'POST',
        [
            'data' => [
                'slices' => $slices,
                'passengers' => $passengers,
                'cabin_class' => $criteria['cabin']
            ]
        ]
    );
}

function mt_duffel_get_offer(string $offerId, bool $returnServices = true): array
{
    $query = $returnServices ? '?return_available_services=true' : '';
    return mt_duffel_request('/air/offers/' . rawurlencode($offerId) . $query, 'GET');
}
