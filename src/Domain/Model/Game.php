<?php

namespace App\Domain\Model;

use App\Domain\Repository\GameRepository;
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

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'The tournament should not be null.')]
    private ?Tournament $tournament = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, name: "player1_id", referencedColumnName: "id")]
    private ?Player $player1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, name: "player2_id", referencedColumnName: "id")]
    private ?Player $player2 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, name: "player_winner_id", referencedColumnName: "id")]
    private ?Player $winner = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: "next_game_id", referencedColumnName: "id")]
    private ?self $nextGame = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotNull(message: 'The stage should not be null.')]
    private ?int $stage = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNextGame(): ?self
    {
        return $this->nextGame;
    }

    public function setNextGame(?self $nextGame): static
    {
        $this->nextGame = $nextGame;
        return $this;
    }

    public function getStage(): ?int
    {
        return $this->stage;
    }

    public function setStage(int $stage): static
    {
        $this->stage = $stage;
        return $this;
    }
}
