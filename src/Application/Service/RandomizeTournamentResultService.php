<?php

namespace App\Application\Service;

use App\Domain\Model\Game;
use App\Domain\Model\Tournament;
use App\Domain\Repository\GameRepository;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\TournamentRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;

class RandomizeTournamentResultService
{
    private GameRepository $gameRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;
    private DetermineGameWinnerService $determineGameWinnerService;
    private ValidatorInterface $validator;

    public function __construct(
        GameRepository $gameRepository,
        PlayerRepository $playerRepository,
        TournamentRepository $tournamentRepository,
        DetermineGameWinnerService $determineGameWinnerService,
        ValidatorInterface $validator
    ) {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->determineGameWinnerService = $determineGameWinnerService;
        $this->validator = $validator;
    }

    public function execute(Tournament $tournament, array $playerIds): void
    {
        if ($this->tournamentRepository->getCountGames($tournament->getId()) === 0) {
            throw new InvalidArgumentException('Tournament has no games.');
        }

        $games = $this->gameRepository->findBy(['tournament' => $tournament], ['stage' => 'DESC']);

        if (!$games) {
            throw new InvalidArgumentException('No games found for the tournament.');
        }

        foreach ($games as $game) {
            $winner = $this->determineGameWinnerService->execute($game);
            $game->setWinner($winner);

            $nextGame = $game->getNextGame();
            if ($nextGame) {
                if (!$nextGame->getPlayer1()) {
                    $nextGame->setPlayer1($winner);
                } elseif (!$nextGame->getPlayer2()) {
                    $nextGame->setPlayer2($winner);
                } else {
                    throw new \Exception('Both players are already set for the next game.');
                }

                $this->gameRepository->save($nextGame);
            }

            $this->gameRepository->save($game);
        }
    }
}
