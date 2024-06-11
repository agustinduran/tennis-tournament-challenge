<?php

namespace App\Application\Service\Seed;

use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\PlayerPropertyRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeedPlayerPropertyValueService
{
    private EntityManagerInterface $entityManager;
    private PlayerRepository $playerRepository;
    private PlayerPropertyRepository $playerPropertyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        PlayerRepository $playerRepository,
        PlayerPropertyRepository $playerPropertyRepository
    ) {
        $this->entityManager = $entityManager;
        $this->playerRepository = $playerRepository;
        $this->playerPropertyRepository = $playerPropertyRepository;
    }

    public function execute()
    {
        $playerPropertyValues = [
            // JM Del Potro
            ['Juan Martín del Potro', 'Fuerza', 90],
            ['Juan Martín del Potro', 'Velocidad de Desplazamiento', 60],
            // Rafael Nadal
            ['Rafael Nadal', 'Fuerza', 85],
            ['Rafael Nadal', 'Velocidad de Desplazamiento', 70],
            // Roger Federer
            ['Roger Federer', 'Fuerza', 80],
            ['Roger Federer', 'Velocidad de Desplazamiento', 75],
            // Agustín Durán
            ['Agustín Durán', 'Fuerza', 50],
            ['Agustín Durán', 'Velocidad de Desplazamiento', 40],
        ];

        foreach ($playerPropertyValues as $valueData) {
            $player = $this->playerRepository->findOneBy(['fullName' => $valueData[0]]);
            $property = $this->playerPropertyRepository->findOneBy(['name' => $valueData[1]]);

            $value = new PlayerPropertyValue();
            $value->setPlayer($player);
            $value->setProperty($property);
            $value->setValue($valueData[2]);

            $this->entityManager->persist($value);
        }
        $this->entityManager->flush();
    }
}
