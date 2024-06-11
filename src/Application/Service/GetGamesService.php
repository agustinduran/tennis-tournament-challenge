<?php

namespace App\Application\Service;

use App\Domain\Model\Game;
use App\Domain\Repository\GameRepository;

class GetGamesService
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * @return Game[]
     */
    public function execute(): array
    {
        return $this->gameRepository->findAll();
    }
}
