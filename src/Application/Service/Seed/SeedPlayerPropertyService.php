<?php

namespace App\Application\Service\Seed;

use App\Domain\Model\PlayerProperty;
use App\Domain\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeedPlayerPropertyService
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
        $properties = [['Fuerza', 'Masculino'], ['Velocidad de Desplazamiento', 'Masculino'], ['Tiempo de Reacción', 'Femenino']];

        foreach ($properties as $valueData) {
            $property = new PlayerProperty();
            $property->setName($valueData[0]);
            $property->setGender($this->genderRepository->findOneBy(['name' => $valueData[1]]));
            $this->entityManager->persist($property);
        }
        $this->entityManager->flush();
    }
}
