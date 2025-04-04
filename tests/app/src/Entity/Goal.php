<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SoureCode\Bundle\Unit\Doctrine\DBAL\Types\LengthType;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;

#[ORM\Entity()]
class Goal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: LengthType::NAME)]
    private ?LengthUnitInterface $target = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?LengthUnitInterface
    {
        return $this->target;
    }

    public function setTarget(LengthUnitInterface $target): static
    {
        $this->target = $target;

        return $this;
    }
}
