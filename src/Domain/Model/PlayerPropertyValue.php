<?php

namespace App\Domain\Model;

use App\Domain\Repository\PlayerPropertyValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerPropertyValueRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_property_value', columns: ['player_id', 'property_id'])]
class PlayerPropertyValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'property')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The player should not be null.')]
    private ?Player $player = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The property should not be null.')]
    private ?PlayerProperty $property = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'The value should not be blank.')]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: 'The value must be between {{ min }} and {{ max }}.',
    )]
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
