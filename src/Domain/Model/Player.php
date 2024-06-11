<?php

namespace App\Domain\Model;

use App\Domain\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "full_name", length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $fullName = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 100)]
    private ?int $habilityLevel = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 1, max: 100)]
    private ?int $luckyLevel = null;

    #[ORM\ManyToOne(inversedBy: 'gender')]
    #[ORM\JoinColumn(nullable: false)]
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
