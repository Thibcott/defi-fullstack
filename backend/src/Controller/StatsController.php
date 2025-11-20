<?php

namespace App\Controller;

use App\Application\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    #[Route('/stats/analytic-codes', name: 'stats_analytic_codes', methods: ['GET'])]
    public function statsByAnalyticCode(TripService $tripService): JsonResponse
    {
        $stats = $tripService->getStatsByAnalyticCode();

        return $this->json($stats);
    }
}
