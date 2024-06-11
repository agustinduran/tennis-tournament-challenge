<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Player;
use App\Domain\Repository\PlayerRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePlayerRepository extends ServiceEntityRepository implements PlayerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function save(Player $player): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($player);
        $entityManager->flush();
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     * @return Player|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Player
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Player|null
     */
    public function findOneBy(array $criteria, ?array $orderBy = null): ?Player
    {
        return parent::findOneBy($criteria, $orderBy);
    }

}
