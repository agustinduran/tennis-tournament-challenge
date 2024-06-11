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

    /**
     * @param int $id
     * @return Tournament|null
     */
    public function find(int $id): ?Tournament;

    /**
     * @param string|null $date
     * @param int|null $genderId
     * @return Tournament[]
     */
    public function getCountGames(int $tournamentId): int;
    
    /**
     * @param string|null $date
     * @param int|null $genderId
     * @return Tournament[]
     */
    public function findByFilters(?string $date, ?int $genderId): array;
}
