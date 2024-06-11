<?php

namespace App\Application\Service\Seed;

use App\Domain\Model\Gender;
use App\Domain\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeedGenderService
{
    private EntityManagerInterface $entityManager;
    private GenderRepository $genderRepository;

    public function __construct(EntityManagerInterface $entityManager, GenderRepository $genderRepository)
    {
        $this->entityManager = $entityManager;
        $this->genderRepository = $genderRepository;
    }

    public function execute()
    {
        $genders = ['Masculino', 'Femenino'];

        foreach ($genders as $genderName) {
            $gender = new Gender();
            $gender->setName($genderName);
            $this->entityManager->persist($gender);
        }

        $this->entityManager->flush();
    }
}
