<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerPropertyValueRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePlayerPropertyValueRepository extends ServiceEntityRepository implements PlayerPropertyValueRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerPropertyValue::class);
    }

    public function save(PlayerPropertyValue $playerPropertyValue): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($playerPropertyValue);
        $entityManager->flush();
    }

    /**
     * @return PlayerPropertyValue[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     * @return PlayerPropertyValue|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?PlayerPropertyValue
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @param int $playerId
     * @return PlayerPropertyValue[]
     */
    public function findByPlayer(int $playerId): array
    {
        return $this->findBy(['player' => $playerId]);
    }
}
