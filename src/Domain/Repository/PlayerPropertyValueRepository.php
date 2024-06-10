<?php

namespace App\Domain\Repository;

use App\Domain\Model\PlayerPropertyValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PlayerPropertyValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerPropertyValue::class);
    }

    public function save(PlayerPropertyValue $playerPropertyValue): void
    {
        $this->_em->persist($playerPropertyValue);
        $this->_em->flush();
    }
}
