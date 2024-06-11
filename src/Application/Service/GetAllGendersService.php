<?php

namespace App\Application\Service;

use App\Domain\Repository\GenderRepository;

class GetAllGendersService
{
    private GenderRepository $genderRepository;

    public function __construct(GenderRepository $genderRepository)
    {
        $this->genderRepository = $genderRepository;
    }

    public function execute(): array
    {
        return $this->genderRepository->findAll();
    }
}
