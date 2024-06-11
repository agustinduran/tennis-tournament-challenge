<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\Gender;
use App\Domain\Repository\GenderRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineGenderRepository extends ServiceEntityRepository implements GenderRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gender::class);
    }

    public function save(Gender $gender): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($gender);
        $entityManager->flush();
    }

    /**
     * @return Gender[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     * @return Gender|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Gender
    {
        return parent::find($id, $lockMode, $lockVersion);
    }
}
