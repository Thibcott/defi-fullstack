<?php

namespace App\Infrastructure\Loader;

use App\Domain\Model\Station;
use App\Domain\Model\Segment;

class StationsDataLoader
{
    /** @var array<string, Station> */
    private array $stations = [];

    /** @var Segment[] */
    private array $segments = [];

    public function __construct(string $stationsFile, string $distancesFile)
    {
        $this->loadStations($stationsFile);
        $this->loadSegments($distancesFile);
    }

    private function loadStations(string $filePath): void
    {
        $json = json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);

        foreach ($json as $item) {
            $station = new Station(
                $item['id'],
                $item['shortName'],
                $item['longName']
            );

            // On indexe par shortName (MX, CGE, etc.)
            $this->stations[$station->shortName] = $station;
        }
    }

    private function loadSegments(string $filePath): void
    {
        $json = json_decode(file_get_contents($filePath), true, 512, JSON_THROW_ON_ERROR);

        foreach ($json as $network) {
            foreach ($network['distances'] as $item) {
                $this->segments[] = new Segment(
                    $item['parent'],   // fromCode
                    $item['child'],    // toCode
                    $item['distance']
                );
            }
        }
    }

    /**
     * @return array<string, Station>
     */
    public function getStations(): array
    {
        return $this->stations;
    }

    /**
     * @return Segment[]
     */
    public function getSegments(): array
    {
        return $this->segments;
    }
}
