<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\PlayerProperty;
use App\Domain\Repository\PlayerPropertyRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePlayerPropertyRepository extends ServiceEntityRepository implements PlayerPropertyRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerProperty::class);
    }

    public function save(PlayerProperty $property): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($property);
        $entityManager->flush();
    }

    /**
     * @return PlayerProperty[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     * @return PlayerProperty|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?PlayerProperty
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
