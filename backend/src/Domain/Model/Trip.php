<?php

namespace App\Domain\Model;

class Trip
{
    public function __construct(
        public string $fromCode,
        public string $toCode,
        public string $analyticCode,
        public float $distance,
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {}

    public function toArray(): array
    {
        return [
            'from' => $this->fromCode,
            'to' => $this->toCode,
            'analyticCode' => $this->analyticCode,
            'distance' => $this->distance,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}
