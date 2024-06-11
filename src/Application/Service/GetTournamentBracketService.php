<?php

namespace App\Application\Service;

use App\Domain\Repository\GameRepository;
use App\Domain\Repository\TournamentRepository;
use InvalidArgumentException;

class GetTournamentBracketService
{
    private GameRepository $gameRepository;
    private TournamentRepository $tournamentRepository;

    public function __construct(GameRepository $gameRepository, TournamentRepository $tournamentRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->tournamentRepository = $tournamentRepository;
    }

    public function execute(int $tournamentId): array
    {
        $tournament = $this->tournamentRepository->find($tournamentId);

        if (!$tournament) {
            throw new InvalidArgumentException('Tournament not found.');
        }

        $games = $this->gameRepository->findBy(['tournament' => $tournament], ['stage' => 'ASC']);
        $bracket = $this->buildBracket($games);

        return $bracket;
    }

    private function buildBracket(array $games): array
    {
        $bracket = [];

        foreach ($games as $game) {
            $stage = $game->getStage();
            $bracket[$stage][] = [
                'gameId' => $game->getId(),
                'player1' => $game->getPlayer1() ? $game->getPlayer1()->getFullName() : null,
                'player2' => $game->getPlayer2() ? $game->getPlayer2()->getFullName() : null,
                'winner' => $game->getWinner() ? $game->getWinner()->getFullName() : null,
            ];
        }

        return $bracket;
    }

}
