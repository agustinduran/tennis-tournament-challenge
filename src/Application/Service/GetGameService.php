<?php

namespace App\Application\Service;

use App\Domain\Model\Game;
use App\Domain\Repository\GameRepository;

class GetGameService
{
    private GameRepository $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function execute(int $id): ?Game
    {
        return $this->gameRepository->find($id);
    }
}
