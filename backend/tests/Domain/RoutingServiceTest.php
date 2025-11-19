<?php

namespace App\Tests\Domain;

use App\Domain\Service\RoutingService;
use App\Infrastructure\Loader\StationsDataLoader;
use PHPUnit\Framework\TestCase;

class RoutingServiceTest extends TestCase
{
    private function createRoutingService(): RoutingService
    {
        $backendDir = dirname(__DIR__, 2);         // .../backend
        $projectRoot = dirname($backendDir);       // .../defi-fullstack

        $stationsFile = $projectRoot . '/stations.json';
        $distancesFile = $projectRoot . '/distances.json';

        $loader = new StationsDataLoader($stationsFile, $distancesFile);

        return new RoutingService($loader);
    }

    public function testDirectDistanceBetweenMxAndCge(): void
    {
        $service = $this->createRoutingService();

        $distance = $service->calculateDistance('MX', 'CGE');

        $this->assertEquals(0.65, $distance, '', 0.0001);
    }

    public function testDistanceBetweenMxAndVuarIsSumOfTwoSegments(): void
    {
        $service = $this->createRoutingService();

        $distance = $service->calculateDistance('MX', 'VUAR');

        // MX -> CGE (0.65) + CGE -> VUAR (0.35) = 1.0
        $this->assertEquals(1.0, $distance, '', 0.0001);
    }
}
