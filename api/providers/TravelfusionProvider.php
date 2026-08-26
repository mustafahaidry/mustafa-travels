<?php
declare(strict_types=1);
require_once __DIR__.'/FlightProviderInterface.php';
final class TravelfusionProvider implements FlightProviderInterface {
    public function code(): string{return 'travelfusion';}
    public function search(array $criteria): array{return ['ok'=>false,'error'=>'Travelfusion credentials not configured yet.','status'=>0,'offers'=>[]];}
    public function getOffer(string $offerId): array{return ['ok'=>false,'error'=>'Travelfusion credentials not configured yet.','status'=>0,'offer'=>[]];}
}
