<?php

namespace App\Application\Command;

use App\Domain\Model\Gender;
use App\Domain\Model\Tournament;
use App\Domain\Model\Player;
use App\Domain\Model\PlayerProperty;
use App\Domain\Model\PlayerPropertyValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SeedDatabaseCommand extends Command
{
    protected static $defaultName = 'app:seed-database';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Seed the database with rows');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->seedGenders();
        $this->seedTournaments();
        $this->seedPlayers();
        $this->seedPlayerProperties();
        $this->seedPlayerPropertyValues();

        $output->writeln('Database seeded successfully.');

        return Command::SUCCESS;
    }

    private function seedGenders()
    {
        $genders = ['Masculino', 'Femenino'];

        foreach ($genders as $genderName) {
            $gender = new Gender();
            $gender->setName($genderName);
            $this->entityManager->persist($gender);
        }

        $this->entityManager->flush();
    }

    private function seedTournaments()
    {
        $gender = $this->entityManager->getRepository(Gender::class)->findOneBy(['name' => 'Masculino']);

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

    private function seedPlayers()
    {
        $gender = $this->entityManager->getRepository(Gender::class)->findOneBy(['name' => 'Masculino']);

        $players = [
            ['Juan Martín del Potro', 60, 50, $gender],
            ['Rafael Nadal', 80, 70, $gender],
            ['Roger Federer', 90, 80, $gender],
            ['Agustín Durán', 0, 0, $gender]
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

    private function seedPlayerProperties()
    {
        $properties = ['Fuerza', 'Velocidad de Desplazamiento', 'Tiempo de Reacción'];

        foreach ($properties as $name) {
            $property = new PlayerProperty();
            $property->setName($name);
            $this->entityManager->persist($property);
        }
        $this->entityManager->flush();
    }

    private function seedPlayerPropertyValues()
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
            $player = $this->entityManager->getRepository(Player::class)->findOneBy(['fullName' => $valueData[0]]);
            $property = $this->entityManager->getRepository(PlayerProperty::class)->findOneBy(['name' => $valueData[1]]);

            $value = new PlayerPropertyValue();
            $value->setPlayer($player);
            $value->setProperty($property);
            $value->setValue($valueData[2]);

            $this->entityManager->persist($value);
        }
        $this->entityManager->flush();
    }
}
