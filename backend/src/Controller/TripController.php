<?php

namespace App\Controller;

use App\Application\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/trips', name: 'trips_create', methods: ['POST'])]
    public function create(Request $request, TripService $tripService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['from'], $data['to'], $data['analyticCode'])) {
            return $this->json(
                ['error' => 'Fields "from", "to" and "analyticCode" are required'],
                400
            );
        }

        $trip = $tripService->createTrip(
            $data['from'],
            $data['to'],
            $data['analyticCode']
        );

        return $this->json($trip->toArray(), 201);
    }

    #[Route('/trips', name: 'trips_list', methods: ['GET'])]
    public function list(TripService $tripService): JsonResponse
    {
        $trips = array_map(
            fn($trip) => $trip->toArray(),
            $tripService->getTrips()
        );

        return $this->json($trips);
    }

    #[Route('/stats/analytic-codes', name: 'stats_analytic_codes', methods: ['GET'])]
    public function stats(TripService $tripService): JsonResponse
    {
        return $this->json($tripService->getStatsByAnalyticCode());
    }
}
