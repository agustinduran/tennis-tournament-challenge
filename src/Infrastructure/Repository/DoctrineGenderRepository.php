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

    /**
     * @return Gender[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }
}
