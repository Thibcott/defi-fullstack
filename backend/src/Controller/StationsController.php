<?php

namespace App\Controller;

use App\Infrastructure\Loader\StationsDataLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StationsController extends AbstractController
{
    #[Route('/stations', name: 'stations_index', methods: ['GET'])]
    public function index(StationsDataLoader $loader): JsonResponse
    {
        // On transforme les objets Station en tableaux
        $stations = array_map(
            fn ($station) => $station->toArray(),
            $loader->getStations()
        );

        return $this->json($stations);
    }
}
