<?php

namespace App\Application\Service;

use App\Domain\Repository\PlayerPropertyValueRepository;

class GetPlayerPropertyValuesService
{
    private PlayerPropertyValueRepository $playerPropertyValueRepository;

    public function __construct(PlayerPropertyValueRepository $playerPropertyValueRepository)
    {
        $this->playerPropertyValueRepository = $playerPropertyValueRepository;
    }

    public function execute(): array
    {
        return $this->playerPropertyValueRepository->findAll();
    }
}
