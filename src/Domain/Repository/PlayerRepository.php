<?php

namespace App\Domain\Repository;

use App\Domain\Model\Player;

interface PlayerRepository
{
    public function save(Player $player): void;

    public function findAll(): array;

    public function find(int $id): ?Player;
}
