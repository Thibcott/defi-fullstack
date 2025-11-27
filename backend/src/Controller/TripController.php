<?php

namespace App\Controller;

use App\Application\Dto\RouteRequest;
use App\Application\Service\TripService;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/routes', name: 'routes_create', methods: ['POST'])]
    public function create(Request $request, TripService $tripService): JsonResponse
    {
        $raw = $request->getContent() ?: '';
        error_log('POST /api/v1/routes raw payload: ' . $raw);
        $data = json_decode($raw, true);

        // Fallback for form-data / x-www-form-urlencoded or empty JSON parsing
        if (!is_array($data) || $data === []) {
            $data = $request->request->all();
        }

        try {
            $routeRequest = RouteRequest::fromArray($data);
            $result = $tripService->createTrip($routeRequest);
        } catch (InvalidArgumentException $e) {
            return $this->json(
                ['error' => $e->getMessage(), 'raw' => $raw, 'data' => $data],
                422
            );
        } catch (\Throwable $e) {
            return $this->json(
                ['error' => 'Internal error: ' . $e->getMessage()],
                500
            );
        }

        return $this->json(
            array_merge($result['trip']->toArray(), ['path' => $result['path']]),
            201
        );
    }

    #[Route('/routes', name: 'routes_list', methods: ['GET'])]
    public function list(TripService $tripService): JsonResponse
    {
        $trips = array_map(
            fn ($trip) => $trip->toArray(),
            $tripService->getTrips()
        );

        return $this->json($trips);
    }
}
