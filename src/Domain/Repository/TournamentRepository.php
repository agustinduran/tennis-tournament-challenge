<?php

namespace App\Domain\Repository;

use App\Domain\Model\Tournament;

interface TournamentRepository
{
    public function save(Tournament $tournament): void;

    /**
     * @return Tournament[]
     */
    public function findAll(): array;

    public function find(int $id): ?Tournament;

    public function getCountGames(int $tournamentId): int;
}
