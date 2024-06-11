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

class RandomizeTournamentResultService
{
    private GameRepository $gameRepository;
    private PlayerRepository $playerRepository;
    private TournamentRepository $tournamentRepository;
    private ValidatorInterface $validator;

    public function __construct(
        GameRepository $gameRepository,
        PlayerRepository $playerRepository,
        TournamentRepository $tournamentRepository,
        ValidatorInterface $validator
    ) {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->tournamentRepository = $tournamentRepository;
        $this->validator = $validator;
    }

    public function execute(Tournament $tournament, array $playerIds): void
    {

    }
}
