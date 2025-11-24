<?php

namespace App\Domain\Model;

class Segment
{
    public function __construct(
        public string $fromCode,
        public string $toCode,
        public float $distance
    ) {
        
    }

    public function toArray(): array
    {
        return [
            'from' => $this->fromCode,
            'to' => $this->toCode,
            'distance' => $this->distance,
        ];
    }
}
