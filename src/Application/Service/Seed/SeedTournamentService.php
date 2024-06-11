<?php

namespace App\Application\Service\Seed;

use App\Domain\Model\Tournament;
use App\Domain\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeedTournamentService
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
        $gender = $this->genderRepository->findOneBy(['name' => 'Masculino']);

        if (!$gender) {
            throw new \Exception('Género no encontrado');
        }

        $tournament = new Tournament();
        $tournament->setDate(new \DateTime());
        $tournament->setTitle("Torneo de prueba");
        $tournament->setGender($gender);
        $this->entityManager->persist($tournament);
        $this->entityManager->flush();
    }
}
