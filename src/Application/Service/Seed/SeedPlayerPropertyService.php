<?php

namespace App\Application\Service\Seed;

use App\Domain\Model\PlayerProperty;
use Doctrine\ORM\EntityManagerInterface;

class SeedPlayerPropertyService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute()
    {
        $properties = ['Fuerza', 'Velocidad de Desplazamiento', 'Tiempo de Reacción'];

        foreach ($properties as $name) {
            $property = new PlayerProperty();
            $property->setName($name);
            $this->entityManager->persist($property);
        }
        $this->entityManager->flush();
    }
}
