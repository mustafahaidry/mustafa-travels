<?php
declare(strict_types=1);
require_once __DIR__.'/providers/DuffelProvider.php';
require_once __DIR__.'/providers/PKFareProvider.php';
require_once __DIR__.'/providers/TravelfusionProvider.php';
function mt_flight_provider(string $code=''): FlightProviderInterface {
    $requested=strtolower($code!==''?$code:(getenv('FLIGHT_PROVIDER')?:'duffel'));
    return match($requested){'pkfare'=>new PKFareProvider(),'travelfusion'=>new TravelfusionProvider(),default=>new DuffelProvider()};
}
