<?php

namespace App\Application\Service;

use App\Domain\Model\Game;
use App\Domain\Model\Tournament;
use App\Domain\Repository\GameRepository;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\TournamentRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class GenerateGamesService
{
    private GameRepository $gameRepository;
    private PlayerRepository $playerRepository;
    private ValidatorInterface $validator;

    public function __construct(
        GameRepository $gameRepository,
        PlayerRepository $playerRepository,
        ValidatorInterface $validator
    ) {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->validator = $validator;
    }

    public function execute(Tournament $tournament, array $playerIds): void
    {
        if (count($playerIds) < 2 || (count($playerIds) & (count($playerIds) - 1)) !== 0) {
            throw new InvalidArgumentException('Player count must be a power of 2 and at least 2.');
        }

        // Obtengo y valido jugadores
        $players = [];
        foreach ($playerIds as $playerId) {
            $player = $this->playerRepository->find($playerId);
            if (!$player) {
                throw new InvalidArgumentException("Player with ID $playerId not found.");
            }
            $players[] = $player;
        }

        // Randomizar jugadores
        shuffle($players);

        $stage = log(count($players), 2);

        // FIXME:
        // $numberGamesOnStage = $stage * 2;

        for ($stageBuffer = 0; $stageBuffer <= $stage; $stageBuffer++) {
            // Cantidad de juegos por etapa
            $numberGamesOnStage = $stageBuffer * 2;

            $nextGames = [];
            for ($i = 0; $i < $numberGamesOnStage; $i++) {
                $game = new Game();
                $game->setTournament($tournament);
                $game->setStage($stageBuffer);

                // Si es el último stage del grafo
                if ($stageBuffer === $stage) {
                    // Obtengo el primero y el segundo de la lista
                    $game->setPlayer1($players[0]);
                    $game->setPlayer2($players[1]);
        
                    // Elimino los dos primeros de la lista
                    if (count($players) > 1)
                        $players = array_slice($players, 1);
                }

                // Inserto el siguiente juego
                if ($nextGames) {
                    $game->setNextGame($nextGames[$i / 2]);
                }

                // Validación de errores
                $errors = $this->validator->validate($game);
                if (count($errors) > 0) {
                    throw new ValidationFailedException($game, $errors);
                }

                $this->gameRepository->save($game);

                $nextGames[] = $game;
            }
        }

        // Linkeo los siguientes juegos
        // foreach ($games as $stage => $stageGames) {
        //     foreach ($stageGames as $index => $game) {
        //         if (isset($games[$stage - 1])) {
        //             $nextGame = $games[$stage - 1][floor($index / 2)];
        //             $game->setNextGame($nextGame);
        //         }
        //     }
        // }

        // while (count($players) > 1) {
        //     $stage--;
        //     $nextStageGames = [];
        //     for ($i = 0; $i < count($players); $i += 2) {
                
        //     }
        //     $players = $nextStageGames;
        // }
        

    }
}
