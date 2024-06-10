<?php

namespace App\Entity;

use App\Repository\GenderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenderRepository::class)]
class Gender
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Tournament>
     */
    #[ORM\OneToMany(targetEntity: Tournament::class, mappedBy: 'gender')]
    private Collection $tournaments;

    /**
     * @var Collection<int, Player>
     */
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'gender')]
    private Collection $gender;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
        $this->gender = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Tournament>
     */
    public function getTournaments(): Collection
    {
        return $this->tournaments;
    }

    public function addTournament(Tournament $tournament): static
    {
        if (!$this->tournaments->contains($tournament)) {
            $this->tournaments->add($tournament);
            $tournament->setGender($this);
        }

        return $this;
    }

    public function removeTournament(Tournament $tournament): static
    {
        if ($this->tournaments->removeElement($tournament)) {
            // set the owning side to null (unless already changed)
            if ($tournament->getGender() === $this) {
                $tournament->setGender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getGender(): Collection
    {
        return $this->gender;
    }

    public function addGender(Player $gender): static
    {
        if (!$this->gender->contains($gender)) {
            $this->gender->add($gender);
            $gender->setGender($this);
        }

        return $this;
    }

    public function removeGender(Player $gender): static
    {
        if ($this->gender->removeElement($gender)) {
            // set the owning side to null (unless already changed)
            if ($gender->getGender() === $this) {
                $gender->setGender(null);
            }
        }

        return $this;
    }
}
