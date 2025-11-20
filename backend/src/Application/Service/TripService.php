<?php

namespace App\Application\Service;

use App\Domain\Service\RoutingService;
use App\Entity\Trip;
use App\Repository\TripRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class TripService
{
    public function __construct(
        private RoutingService $routingService,
        private EntityManagerInterface $em,
        private TripRepository $tripRepository
    ) {}

    public function createTrip(string $from, string $to, string $analyticCode): Trip
    {
        $distance = $this->routingService->calculateDistance($from, $to);

        if ($distance <= 0) {
            throw new InvalidArgumentException(sprintf(
                'Impossible de calculer une distance positive entre %s et %s',
                $from,
                $to
            ));
        }

        $trip = new Trip($from, $to, $analyticCode, $distance);

        $this->em->persist($trip);
        $this->em->flush();

        return $trip;
    }

    /**
     * @return Trip[]
     */
    public function getTrips(): array
    {
        return $this->tripRepository->findBy([], ['createdAt' => 'DESC']);
    }

    /**
     * @return array<int, array{analyticCode: string, count: int, totalDistance: float}>
     */
    public function getStatsByAnalyticCode(): array
    {
        return $this->tripRepository->getStatsByAnalyticCode();
    }
}
