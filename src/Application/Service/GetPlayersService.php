<?php

namespace App\Application\Service;

use App\Domain\Repository\PlayerRepository;

class GetPlayersService
{
    private PlayerRepository $playerRepository;

    public function __construct(PlayerRepository $playerRepository)
    {
        $this->playerRepository = $playerRepository;
    }

    public function execute(): array
    {
        return $this->playerRepository->findAll();
    }
}
