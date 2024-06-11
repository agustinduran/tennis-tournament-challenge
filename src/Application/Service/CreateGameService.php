<?php

namespace App\Application\Service;

use App\Domain\Model\Game;
use App\Domain\Repository\GameRepository;
use App\Domain\Repository\TournamentRepository;
use App\Domain\Repository\PlayerRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreateGameService
{
    private GameRepository $gameRepository;
    private TournamentRepository $tournamentRepository;
    private PlayerRepository $playerRepository;
    private ValidatorInterface $validator;

    public function __construct(
        GameRepository $gameRepository,
        TournamentRepository $tournamentRepository,
        PlayerRepository $playerRepository,
        ValidatorInterface $validator
    ) {
        $this->gameRepository = $gameRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->playerRepository = $playerRepository;
        $this->validator = $validator;
    }

    public function execute(int $tournamentId, int $playerOneId, int $playerTwoId, ?int $winnerId, ?int $nextGameId, int $stage): Game
    {
        $tournament = $this->tournamentRepository->find($tournamentId);
        if (!$tournament) {
            throw new \InvalidArgumentException('Invalid tournament ID');
        }

        $playerOne = $this->playerRepository->find($playerOneId);
        if (!$playerOne) {
            throw new \InvalidArgumentException('Invalid player one ID');
        }

        $playerTwo = $this->playerRepository->find($playerTwoId);
        if (!$playerTwo) {
            throw new \InvalidArgumentException('Invalid player two ID');
        }

        $winner = null;
        if ($winnerId !== null) {
            $winner = $this->playerRepository->find($winnerId);
            if (!$winner) {
                throw new \InvalidArgumentException('Invalid winner ID');
            }
        }

        $nextGame = null;
        if ($nextGameId !== null) {
            $nextGame = $this->gameRepository->find($nextGameId);
            if (!$nextGame) {
                throw new \InvalidArgumentException('Invalid next game ID');
            }
        }

        $game = new Game();
        $game->setTournament($tournament);
        $game->setPlayer1($playerOne);
        $game->setPlayer2($playerTwo);
        $game->setWinner($winner);
        $game->setNextGame($nextGame);
        $game->setStage($stage);

        $errors = $this->validator->validate($game);

        if (count($errors) > 0) {
            throw new ValidationFailedException($game, $errors);
        }

        $this->gameRepository->save($game);

        return $game;
    }
}
