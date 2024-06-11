<?php

namespace App\Application\Service;

use App\Domain\Model\Game;
use App\Domain\Model\Player;

class DetermineGameWinnerService
{
    private CalculateScoreByPlayerService $calculateScoreByPlayerService;

    public function __construct(CalculateScoreByPlayerService $calculateScoreByPlayerService)
    {
        $this->calculateScoreByPlayerService = $calculateScoreByPlayerService;
    }

    public function execute(Game $game): Player
    {
        $player1 = $game->getPlayer1();
        $player2 = $game->getPlayer2();

        $player1Score = $this->calculateScoreByPlayerService->execute($player1);
        $player2Score = $this->calculateScoreByPlayerService->execute($player2);

        // Si el puntaje es igual, se llama a este mismo método de forma recursiva hasta obtener un ganador
        if ($player1Score === $player2Score) {
            return $this->execute($game);
        }

        return $player1Score > $player2Score ? $player1 : $player2;
    }
}
