<?php

namespace App\Controller;

use App\Domain\Service\RoutingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DistanceController extends AbstractController
{
    #[Route('/distance', methods: ['GET'])]
    public function distance(Request $request, RoutingService $routingService): JsonResponse
    {
        $from = $request->query->get('from');
        $to = $request->query->get('to');

        if (!$from || !$to) {
            return $this->json(
                ['error' => 'Params "from" and "to" are required'],
                400
            );
        }

        $distance = $routingService->calculateDistance($from, $to);

        return $this->json([
            'from' => strtoupper($from),
            'to' => strtoupper($to),
            'distance' => $distance,
        ]);
    }
}
