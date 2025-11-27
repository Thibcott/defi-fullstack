<?php

namespace App\Application\Service;

use App\Application\Dto\RouteRequest;
use App\Domain\Service\RoutingService;
use App\Entity\Trip;
use App\Repository\TripRepository;
use InvalidArgumentException;

class TripService
{
    public function __construct(
        private RoutingService $routingService,
        private TripRepository $tripRepository
    ) {
    }

    /**
     * @return array{trip: Trip, path: string[]}
     */
    public function createTrip(RouteRequest $routeRequest): array
    {
        $route = $this->routingService->calculateRoute(
            $routeRequest->fromStationId,
            $routeRequest->toStationId
        );

        if ($route['distance'] <= 0) {
            throw new InvalidArgumentException(sprintf(
                'Impossible de calculer une distance positive entre %s et %s',
                $routeRequest->fromStationId,
                $routeRequest->toStationId
            ));
        }

        $trip = new Trip(
            $routeRequest->fromStationId,
            $routeRequest->toStationId,
            $routeRequest->analyticCode,
            $route['distance']
        );

        $this->tripRepository->save($trip);

        return ['trip' => $trip, 'path' => $route['path']];
    }

    /**
     * @return Trip[]
     */
    public function getTrips(): array
    {
        return $this->tripRepository->findBy([], ['createdAt' => 'DESC']);
    }

    /**
     * @return array<int, array{analyticCode: string, tripCount: int, totalDistanceKm: float}>
     */
    public function getStatsByAnalyticCode(): array
    {
        return $this->tripRepository->getStatsByAnalyticCode();
    }
}
