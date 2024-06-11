<?php

namespace App\Application\Service;

use App\Domain\Model\Player;
use App\Domain\Repository\GameRepository;
use InvalidArgumentException;

class GetPlayerWinnerTournamentService
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function execute(int $tournamentId): Player
    {
        $finalGame = $this->gameRepository->findByTournamentAndStage($tournamentId, 1);

        if (!$finalGame) {
            throw new InvalidArgumentException('No final game found for this tournament.');
        }

        $winner = $finalGame->getWinner();

        if (!$winner) {
            throw new InvalidArgumentException('No winner found for the final game.');
        }

        return $winner;
    }
}
