<?php

namespace App\Application\Dto;

use InvalidArgumentException;

class RouteRequest
{
    public function __construct(
        public readonly string $fromStationId,
        public readonly string $toStationId,
        public readonly string $analyticCode
    ) {
    }

    public static function fromArray(?array $payload): self
    {
        $payload = $payload ?? [];

        // Backward compat: accept legacy keys "from"/"to"
        if (!isset($payload['fromStationId']) && isset($payload['from'])) {
            $payload['fromStationId'] = $payload['from'];
        }
        if (!isset($payload['toStationId']) && isset($payload['to'])) {
            $payload['toStationId'] = $payload['to'];
        }

        if (empty($payload)) {
            throw new InvalidArgumentException('Requete vide ou invalide');
        }

        foreach (['fromStationId', 'toStationId', 'analyticCode'] as $field) {
            if (!array_key_exists($field, $payload) || trim((string) $payload[$field]) === '') {
                throw new InvalidArgumentException(sprintf('Le champ "%s" est obligatoire', $field));
            }
        }

        $from = strtoupper(trim((string) $payload['fromStationId']));
        $to = strtoupper(trim((string) $payload['toStationId']));
        $analyticCode = trim((string) $payload['analyticCode']);

        self::guardStationLength($from, 'fromStationId');
        self::guardStationLength($to, 'toStationId');

        return new self($from, $to, $analyticCode);
    }

    private static function guardStationLength(string $value, string $field): void
    {
        if (strlen($value) > 10) {
            throw new InvalidArgumentException(sprintf(
                'Le champ "%s" doit contenir au maximum 10 caracteres',
                $field
            ));
        }
    }
}
