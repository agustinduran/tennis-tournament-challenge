<?php

namespace App\Domain\Model;

use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $fullName = null;

    #[ORM\Column]
    private ?int $habilityLevel = null;

    #[ORM\Column]
    private ?int $luckyLevel = null;

    #[ORM\ManyToOne(inversedBy: 'gender')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gender $gender = null;

    public function __construct()
    {
        $this->property = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, PlayerPropertyValue>
     */
    public function getProperty(): Collection
    {
        return $this->property;
    }

}
