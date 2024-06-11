<?php

namespace App\Application\Service;

use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerPropertyValueRepository;

class GetPlayerPropertyValueService
{
    private PlayerPropertyValueRepository $playerPropertyValueRepository;

    public function __construct(PlayerPropertyValueRepository $playerPropertyValueRepository)
    {
        $this->playerPropertyValueRepository = $playerPropertyValueRepository;
    }

    public function execute(int $id): ?PlayerPropertyValue
    {
        return $this->playerPropertyValueRepository->find($id);
    }
}
