<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Game;
use App\Domain\Repository\GameRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGameRepository extends ServiceEntityRepository implements GameRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $game): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($game);
        $entityManager->flush();
    }

    /**
     * @return Game[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     * @return Game|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Game
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
