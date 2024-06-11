<?php

namespace App\Application\Service;

use App\Domain\Repository\PlayerPropertyRepository;

class GetPlayerPropertiesService
{
    private PlayerPropertyRepository $playerPropertyRepository;

    public function __construct(PlayerPropertyRepository $playerPropertyRepository)
    {
        $this->playerPropertyRepository = $playerPropertyRepository;
    }

    public function execute(): array
    {
        return $this->playerPropertyRepository->findAll();
    }
}
