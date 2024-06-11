<?php

namespace App\Domain\Model;

use App\Domain\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\Table(name: "games")]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'The date should not be null.')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The tournament should not be null.')]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The player1 should not be null.')]
    private ?Player $player1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The player2 should not be null.')]
    private ?Player $player2 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Player $winner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): static
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getPlayer1(): ?Player
    {
        return $this->player1;
    }

    public function setPlayer1(?Player $player1): static
    {
        $this->player1 = $player1;

        return $this;
    }

    public function getPlayer2(): ?Player
    {
        return $this->player2;
    }

    public function setPlayer2(?Player $player2): static
    {
        $this->player2 = $player2;

        return $this;
    }

    public function getWinner(): ?Player
    {
        return $this->winner;
    }

    public function setWinner(?Player $winner): static
    {
        $this->winner = $winner;

        return $this;
    }
}
