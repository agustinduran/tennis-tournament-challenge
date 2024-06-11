<?php

namespace App\Domain\Repository;

use App\Domain\Model\PlayerPropertyValue;

interface PlayerPropertyValueRepository
{
    public function save(PlayerPropertyValue $playerPropertyValue): void;

    /**
     * @return PlayerPropertyValue[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return PlayerPropertyValue|null
     */
    public function find(int $id): ?PlayerPropertyValue;

    /**
     * @param int $playerId
     * @return PlayerPropertyValue[]
     */
    public function findByPlayerId(int $playerId): array;

}
