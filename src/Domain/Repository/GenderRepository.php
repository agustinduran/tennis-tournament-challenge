<?php

namespace App\Domain\Repository;

use App\Domain\Model\Gender;

interface GenderRepository
{
    /**
     * @return Gender[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Gender|null
     */
    public function find(int $id): ?Gender;

    /**
     * @param array $criteria
     * @return Gender|null
     */
    public function findOneBy(array $criteria): ?Gender;
}
