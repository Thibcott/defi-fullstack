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
     * Construit le graphe à partir des segments.
     *
     * On considère que les distances sont bidirectionnelles :
     * MX -> CGE et CGE -> MX ont la même distance.
     *
     * @param array $segments
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
        $fromCode = strtoupper($fromCode);
        $toCode = strtoupper($toCode);

        if (!isset($this->graph[$fromCode])) {
            throw new InvalidArgumentException(sprintf('Station de départ inconnue: %s', $fromCode));
        }

        if (!isset($this->graph[$toCode])) {
            throw new InvalidArgumentException(sprintf('Station d\'arrivée inconnue: %s', $toCode));
        }

        if ($fromCode === $toCode) {
            return 0.0;
        }

        // Dijkstra

        // Distances minimales connues depuis $fromCode
        $distances = [];
        // Ensemble des nœuds déjà "finalisés"
        $visited = [];

        // Initialisation
        foreach ($this->graph as $node => $_) {
            $distances[$node] = INF;
        }
        $distances[$fromCode] = 0.0;

        while (true) {
            // 1. Trouver le nœud non visité avec la distance minimale
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

            // Plus de nœuds atteignables
            if ($current === null) {
                break;
            }

            // Si on a atteint la destination, on peut s'arrêter
            if ($current === $toCode) {
                break;
            }

            $visited[$current] = true;

            // 2. Mettre à jour les voisins
            foreach ($this->graph[$current] as $neighbor => $edgeDistance) {
                if (isset($visited[$neighbor])) {
                    continue;
                }

                $newDistance = $distances[$current] + $edgeDistance;

                if ($newDistance < $distances[$neighbor]) {
                    $distances[$neighbor] = $newDistance;
                }
            }
        }

        if ($distances[$toCode] === INF) {
            throw new InvalidArgumentException(sprintf(
                'Aucun chemin trouvé entre %s et %s',
                $fromCode,
                $toCode
            ));
        }

        return $distances[$toCode];
    }
}
