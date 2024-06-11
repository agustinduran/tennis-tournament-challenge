<?php

namespace App\Application\Service\Seed;

use App\Domain\Model\Player;
use App\Domain\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeedPlayerService
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

        $players = [
            ['Juan Martín del Potro', 60, 50, $gender],
            ['Rafael Nadal', 80, 70, $gender],
            ['Roger Federer', 90, 80, $gender],
            ['Agustín Durán', 0, 0, $gender],
            ['Novak Djokovic', 90, 80, $gender],
            ['Guillermo Vilas', 80, 70, $gender],
            ['David Nalbandian', 70, 40, $gender],
            ['Andy Murray', 90, 80, $gender]
        ];

        foreach ($players as $playerData) {
            $player = new Player();
            $player->setFullName($playerData[0]);
            $player->setHabilityLevel($playerData[1]);
            $player->setLuckyLevel($playerData[2]);
            $player->setGender($playerData[3]);
            $this->entityManager->persist($player);
        }
        $this->entityManager->flush();
    }
}
