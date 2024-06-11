<?php

namespace App\Domain\Repository;

use App\Domain\Model\Player;

interface PlayerRepository
{
    public function save(Player $player): void;

    /**
     * @return Player[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Player|null
     */
    public function find(int $id): ?Player;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Player|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Player;
}
