<?php
declare(strict_types=1);
interface FlightProviderInterface {
    public function code(): string;
    public function search(array $criteria): array;
    public function getOffer(string $offerId): array;
}
