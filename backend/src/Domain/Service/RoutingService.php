<?php

namespace App\Domain\Service;

use App\Infrastructure\Loader\StationsDataLoader;
use InvalidArgumentException;

class RoutingService
{
    /**
     * Graphe d'adjacence :
     * [
     *   'MX' => ['CGE' => 0.65, 'AUTRE' => 1.2],
     *   'CGE' => ['MX' => 0.65, 'VUAR' => 0.35],
     *   ...
     * ]
     *
     * @var array<string, array<string, float>>
     */
    private array $graph = [];

    public function __construct(StationsDataLoader $loader)
    {
        $this->buildGraph($loader->getSegments());
    }

    /**
     * Construit le graphe a partir des segments (distances bidirectionnelles).
     */
    private function buildGraph(array $segments): void
    {
        foreach ($segments as $segment) {
            $from = $segment->fromCode;
            $to = $segment->toCode;
            $distance = $segment->distance;

            // Sens from -> to
            if (!isset($this->graph[$from])) {
                $this->graph[$from] = [];
            }
            $this->graph[$from][$to] = $distance;

            // Sens to -> from (on suppose qu'on peut faire demi-tour)
            if (!isset($this->graph[$to])) {
                $this->graph[$to] = [];
            }
            $this->graph[$to][$from] = $distance;
        }
    }

    /**
     * Calcule la distance la plus courte entre deux stations (codes courts, ex: "MX", "GST").
     *
     * @throws InvalidArgumentException si une station est inconnue ou si aucun chemin n'existe
     */
    public function calculateDistance(string $fromCode, string $toCode): float
    {
        return $this->calculateRoute($fromCode, $toCode)['distance'];
    }

    /**
     * Retourne distance et chemin le plus court entre deux stations.
     *
     * @return array{distance: float, path: string[]}
     */
    public function calculateRoute(string $fromCode, string $toCode): array
    {
        $fromCode = strtoupper($fromCode);
        $toCode = strtoupper($toCode);

        if (!isset($this->graph[$fromCode])) {
            throw new InvalidArgumentException(sprintf('Station de depart inconnue: %s', $fromCode));
        }

        if (!isset($this->graph[$toCode])) {
            throw new InvalidArgumentException(sprintf('Station d\'arrivee inconnue: %s', $toCode));
        }

        if ($fromCode === $toCode) {
            return ['distance' => 0.0, 'path' => [$fromCode]];
        }

        $distances = [];
        $previous = [];
        $visited = [];

        foreach ($this->graph as $node => $_) {
            $distances[$node] = INF;
        }
        $distances[$fromCode] = 0.0;

        while (true) {
            $current = null;
            $currentDistance = INF;

            foreach ($distances as $node => $distance) {
                if (isset($visited[$node])) {
                    continue;
                }
                if ($distance < $currentDistance) {
                    $currentDistance = $distance;
                    $current = $node;
                }
            }

            if ($current === null) {
                break;
            }

            if ($current === $toCode) {
                break;
            }

            $visited[$current] = true;

            foreach ($this->graph[$current] as $neighbor => $edgeDistance) {
                if (isset($visited[$neighbor])) {
                    continue;
                }

                $newDistance = $distances[$current] + $edgeDistance;

                if ($newDistance < $distances[$neighbor]) {
                    $distances[$neighbor] = $newDistance;
                    $previous[$neighbor] = $current;
                }
            }
        }

        if ($distances[$toCode] === INF) {
            throw new InvalidArgumentException(sprintf(
                'Aucun chemin trouve entre %s et %s',
                $fromCode,
                $toCode
            ));
        }

        $path = $this->reconstructPath($previous, $fromCode, $toCode);

        return [
            'distance' => $distances[$toCode],
            'path' => $path,
        ];
    }

    /**
     * Reconstitue le chemin le plus court depuis les precedents trouves par Dijkstra.
     *
     * @param array<string, string> $previous
     * @return string[]
     */
    private function reconstructPath(array $previous, string $fromCode, string $toCode): array
    {
        $path = [$toCode];
        $current = $toCode;

        while (isset($previous[$current])) {
            $current = $previous[$current];
            $path[] = $current;

            if ($current === $fromCode) {
                break;
            }
        }

        return array_reverse($path);
    }
}
