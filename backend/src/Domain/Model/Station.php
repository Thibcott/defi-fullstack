<?php

namespace App\Domain\Model;

class Station
{
    public function __construct(
        public int $id,
        public string $shortName,
        public string $longName
    ) {
        
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'shortName' => $this->shortName,
            'longName' => $this->longName,
        ];
    }
}
