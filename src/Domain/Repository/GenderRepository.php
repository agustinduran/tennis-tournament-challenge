<?php

namespace App\Domain\Repository;

use App\Domain\Model\Gender;

interface GenderRepository
{
    /**
     * @return Gender[]
     */
    public function findAll(): array;
}
