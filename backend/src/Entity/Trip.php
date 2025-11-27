<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private string $fromStationId;

    #[ORM\Column(length: 10)]
    private string $toStationId;

    #[ORM\Column(length: 50)]
    private string $analyticCode;

    #[ORM\Column(type: 'float')]
    private float $distanceKm;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        string $fromStationId,
        string $toStationId,
        string $analyticCode,
        float $distanceKm
    ) {
        $this->fromStationId = strtoupper($fromStationId);
        $this->toStationId = strtoupper($toStationId);
        $this->analyticCode = $analyticCode;
        $this->distanceKm = $distanceKm;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromStationId(): string
    {
        return $this->fromStationId;
    }

    public function getToStationId(): string
    {
        return $this->toStationId;
    }

    public function getAnalyticCode(): string
    {
        return $this->analyticCode;
    }

    public function getDistanceKm(): float
    {
        return $this->distanceKm;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'fromStationId' => $this->fromStationId,
            'toStationId' => $this->toStationId,
            'analyticCode' => $this->analyticCode,
            'distanceKm' => $this->distanceKm,
            'createdAt' => $this->createdAt->format(DATE_ATOM),
        ];
    }
}
