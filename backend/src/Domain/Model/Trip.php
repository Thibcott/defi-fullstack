<?php

namespace App\Domain\Model;

class Trip
{
    public function __construct(
        public string $from,
        public string $to,
        public string $analyticCode,
        public float $distance,
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable()
    ) {
        
    }

    public function toArray(): array
    {
        return [
            'from' => $this->from,
            'to' => $this->to,
            'analyticCode' => $this->analyticCode,
            'distance' => $this->distance,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}
