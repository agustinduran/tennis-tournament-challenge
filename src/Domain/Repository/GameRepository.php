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

    /**
     * @param int $id
     * @return Game|null
     */
    public function find(int $id): ?Game;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     * @return Game[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array;

    /**
     * @param array $criteria
     * @return Game|null
     */
    public function findByTournamentAndStage(int $tournamentId, int $stage): ?Game;
}
