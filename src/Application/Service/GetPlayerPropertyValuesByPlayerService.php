<?php

namespace App\Application\Service;

use App\Domain\Repository\PlayerPropertyValueRepository;

class GetPlayerPropertyValuesByPlayerService
{
    private PlayerPropertyValueRepository $playerPropertyValueRepository;

    public function __construct(PlayerPropertyValueRepository $playerPropertyValueRepository)
    {
        $this->playerPropertyValueRepository = $playerPropertyValueRepository;
    }

    public function execute(int $playerId): array
    {
        return $this->playerPropertyValueRepository->findByPlayer($playerId);
    }
}
