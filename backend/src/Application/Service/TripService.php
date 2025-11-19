<?php

namespace App\Application\Service;

use App\Domain\Model\Trip;
use App\Domain\Service\RoutingService;

class TripService
{
    /**
     * @var Trip[]
     */
    private array $trips = [];

    public function __construct(
        private RoutingService $routingService
    ) {}

    /**
     * Crée un trajet, calcule la distance et le garde en mémoire.
     */
    public function createTrip(string $fromCode, string $toCode, string $analyticCode): Trip
    {
        $distance = $this->routingService->calculateDistance($fromCode, $toCode);

        $trip = new Trip(
            strtoupper($fromCode),
            strtoupper($toCode),
            $analyticCode,
            $distance
        );

        $this->trips[] = $trip;

        return $trip;
    }

    /**
     * @return Trip[]
     */
    public function getTrips(): array
    {
        return $this->trips;
    }

    /**
     * Statistiques par code analytique.
     *
     * @return array<int, array{analyticCode:string,count:int,totalDistance:float}>
     */
    public function getStatsByAnalyticCode(): array
    {
        $stats = [];

        foreach ($this->trips as $trip) {
            $code = $trip->analyticCode;

            if (!isset($stats[$code])) {
                $stats[$code] = [
                    'analyticCode' => $code,
                    'count' => 0,
                    'totalDistance' => 0.0,
                ];
            }

            $stats[$code]['count']++;
            $stats[$code]['totalDistance'] += $trip->distance;
        }

        return array_values($stats);
    }
}
