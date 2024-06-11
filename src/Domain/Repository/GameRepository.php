<?php

namespace App\Domain\Repository;

use App\Domain\Model\Game;

interface GameRepository
{
    public function save(Game $game): void;

    /**
     * @return Game[]
     */
    public function findAll(): array;

    public function find(int $id): ?Game;
}
