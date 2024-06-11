<?php

namespace App\Application\Service;

use App\Domain\Model\PlayerProperty;
use App\Domain\Repository\PlayerPropertyRepository;

class GetPlayerPropertyService
{
    private PlayerPropertyRepository $playerPropertyRepository;

    public function __construct(PlayerPropertyRepository $playerPropertyRepository)
    {
        $this->playerPropertyRepository = $playerPropertyRepository;
    }

    public function execute(int $id): ?PlayerProperty
    {
        return $this->playerPropertyRepository->find($id);
    }
}
