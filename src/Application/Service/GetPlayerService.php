<?php

namespace App\Application\Service;

use App\Domain\Model\Player;
use App\Domain\Repository\PlayerRepository;

class GetPlayerService
{
    private PlayerRepository $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function execute(int $id): ?Player
    {
        return $this->playerRepository->find($id);
    }
}
