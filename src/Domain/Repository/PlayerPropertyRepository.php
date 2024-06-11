<?php

namespace App\Domain\Repository;

use App\Domain\Model\PlayerProperty;

interface PlayerPropertyRepository
{
    /**
     * @return PlayerProperty[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return PlayerProperty|null
     */
    public function find(int $id): ?PlayerProperty;
}
