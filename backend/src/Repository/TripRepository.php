<?php

namespace App\Repository;

use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * Retourne les stats par code analytique :
     *  - analyticCode
     *  - tripCount
     *  - totalDistanceKm
     *
     * @return array<int, array{analyticCode: string, tripCount: int, totalDistanceKm: float}>
     */
    public function getStatsByAnalyticCode(): array
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t.analyticCode AS analyticCode')
            ->addSelect('COUNT(t.id) AS count')
            ->addSelect('SUM(t.distanceKm) AS totalDistance')
            ->groupBy('t.analyticCode');

        return array_map(static function (array $row): array {
            return [
                'analyticCode' => $row['analyticCode'],
                'tripCount' => (int) $row['count'],
                'totalDistanceKm' => (float) $row['totalDistance'],
            ];
        }, $qb->getQuery()->getArrayResult());
    }

    public function save(Trip $trip): void
    {
        $em = $this->getEntityManager();
        $em->persist($trip);
        $em->flush();
    }
}
