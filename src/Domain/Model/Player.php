<?php

namespace App\Domain\Model;

use App\Domain\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: 'players')]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'The full name should not be blank.')]
    #[Assert\Length(
        max: 100,
        maxMessage: 'The full name cannot be longer than {{ limit }} characters.'
    )]
    private ?string $fullName = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'The hability level should not be blank.')]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: 'The hability level must be between {{ min }} and {{ max }}.',
    )]
    private ?int $habilityLevel = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'The lucky level should not be blank.')]
    #[Assert\Range(
        min: 0,
        max: 100,
        notInRangeMessage: 'The lucky level must be between {{ min }} and {{ max }}.',
    )]
    private ?int $luckyLevel = null;

    #[ORM\ManyToOne(inversedBy: 'gender')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The gender should not be null.')]
    private ?Gender $gender = null;

    public function __construct() {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getHabilityLevel(): ?int
    {
        return $this->habilityLevel;
    }

    public function setHabilityLevel(int $habilityLevel): static
    {
        $this->habilityLevel = $habilityLevel;

        return $this;
    }

    public function getLuckyLevel(): ?int
    {
        return $this->luckyLevel;
    }

    public function setLuckyLevel(int $luckyLevel): static
    {
        $this->luckyLevel = $luckyLevel;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

}
