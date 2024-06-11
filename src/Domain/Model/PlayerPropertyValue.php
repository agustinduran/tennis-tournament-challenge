<?php

namespace App\Domain\Model;

use App\Domain\Model\Player;
use App\Domain\Model\PlayerProperty;
use App\Domain\Repository\PlayerPropertyValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerPropertyValueRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_player_property', columns: ['player_id', 'property_id'])]
class PlayerPropertyValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'property')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PlayerProperty $property = null;

    #[ORM\Column]
    private ?int $value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getProperty(): ?PlayerProperty
    {
        return $this->property;
    }

    public function setProperty(?PlayerProperty $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
