<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\DistanceType;
use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\DurationType;
use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Duration;

#[ORM\Entity()]
class Goal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: DistanceType::NAME, nullable: true)]
    private ?Distance $targetDistance = null;

    #[ORM\Column(type: DurationType::NAME, nullable: true)]
    private ?Duration $targetDuration = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTargetDistance(): ?Distance
    {
        return $this->targetDistance;
    }

    public function setTargetDistance(Distance $targetDistance): static
    {
        $this->targetDistance = $targetDistance;

        return $this;
    }

    public function getTargetDuration(): ?Duration
    {
        return $this->targetDuration;
    }

    public function setTargetDuration(?Duration $targetDuration): Goal
    {
        $this->targetDuration = $targetDuration;
        return $this;
    }
}
