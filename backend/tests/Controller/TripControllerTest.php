<?php

namespace App\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TripControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        /** @var EntityManagerInterface $em */
        $em = self::getContainer()->get(EntityManagerInterface::class);
        $this->em = $em;

        $schemaTool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropDatabase();
        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }
    }

    public function testCreateRouteReturnsPathAndDistance(): void
    {
        $payload = [
            'fromStationId' => 'MX',
            'toStationId' => 'CGE',
            'analyticCode' => 'ANA-1',
        ];

        $this->client->jsonRequest('POST', '/api/v1/routes', $payload);

        $response = $this->client->getResponse();
        $this->assertSame(201, $response->getStatusCode(), $response->getContent());

        $data = json_decode($response->getContent(), true);

        $this->assertSame($payload['fromStationId'], $data['fromStationId']);
        $this->assertSame($payload['toStationId'], $data['toStationId']);
        $this->assertSame($payload['analyticCode'], $data['analyticCode']);
        $this->assertGreaterThan(0, $data['distanceKm']);
        $this->assertIsArray($data['path']);
        $this->assertNotEmpty($data['path']);
    }

    public function testRejectsStationCodeLongerThanTenCharacters(): void
    {
        $payload = [
            'fromStationId' => 'STATION-TOO-LONG',
            'toStationId' => 'CGE',
            'analyticCode' => 'ANA-1',
        ];

        $this->client->jsonRequest('POST', '/api/v1/routes', $payload);

        $response = $this->client->getResponse();
        $this->assertSame(422, $response->getStatusCode());
    }

    public function testStatsEndpointAggregatesTrips(): void
    {
        $this->client->jsonRequest('POST', '/api/v1/routes', [
            'fromStationId' => 'MX',
            'toStationId' => 'CGE',
            'analyticCode' => 'ANA-1',
        ]);
        $this->client->jsonRequest('POST', '/api/v1/routes', [
            'fromStationId' => 'CGE',
            'toStationId' => 'VUAR',
            'analyticCode' => 'ANA-1',
        ]);

        $this->client->request('GET', '/api/v1/stats');

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode(), $response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertCount(1, $data);
        $this->assertSame('ANA-1', $data[0]['analyticCode']);
        $this->assertSame(2, $data[0]['tripCount']);
        $this->assertGreaterThan(0, $data[0]['totalDistanceKm']);
    }
}
