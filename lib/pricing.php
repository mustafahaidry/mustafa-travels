<?php
declare(strict_types=1);

/**
 * Mustafa Travels flight selling-price rules.
 * Default markup is 4%. It can be changed later in Render with
 * FLIGHT_MARKUP_PERCENT without touching the booking code.
 */
function mt_flight_markup_percent(): float
{
    $raw = getenv('FLIGHT_MARKUP_PERCENT');
    $pct = ($raw === false || trim((string)$raw) === '') ? 4.0 : (float)$raw;
    return max(0.0, min(100.0, $pct));
}

function mt_flight_markup_amount(float $duffelCost): float
{
    return round(max(0.0, $duffelCost) * mt_flight_markup_percent() / 100, 2);
}

function mt_flight_sell_price(float $duffelCost): float
{
    return round(max(0.0, $duffelCost) + mt_flight_markup_amount($duffelCost), 2);
}
