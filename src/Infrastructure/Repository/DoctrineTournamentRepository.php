<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Tournament;
use App\Domain\Repository\TournamentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineTournamentRepository extends ServiceEntityRepository implements TournamentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournament::class);
    }

    public function save(Tournament $tournament): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($tournament);
        $entityManager->flush();
    }

    /**
     * @return Tournament[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     * @return Tournament|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Tournament
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
