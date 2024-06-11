<?php

namespace App\Tests\Application\Service;

use App\Application\Service\CreateTournamentService;
use App\Domain\Model\Gender;
use App\Domain\Model\Tournament;
use App\Domain\Repository\TournamentRepository;
use App\Domain\Repository\GenderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CreateTournamentServiceTest extends KernelTestCase
{
    private $tournamentRepository;
    private $genderRepository;
    private $createTournamentService;
    private $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->tournamentRepository = $this->createMock(TournamentRepository::class);
        $this->genderRepository = $this->createMock(GenderRepository::class);
        $this->validator = self::$container->get(ValidatorInterface::class);

        $tournamentRepository = $this->createMock(TournamentRepository::class);
        $genderRepository = $this->createMock(GenderRepository::class);

        $this->createTournamentService = new CreateTournamentService(
            $tournamentRepository,
            $genderRepository,
            $this->validator
        );
    }

    public function testCreateTournament(): void
    {
        $gender = new Gender();
        
        // Utilizando reflexión para establecer el ID
        $reflection = new \ReflectionClass($gender);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($gender, 1);

        $this->genderRepository->method('find')->willReturn($gender);

        $tournament = new Tournament();
        $tournament->setTitle('Roland Garros');
        $tournament->setDate(new \DateTime('2024-06-10'));
        $tournament->setGender($gender);

        $this->tournamentRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($tournament));

        $this->createTournamentService->execute('Roland Garros', new \DateTime('2024-06-10'), 1);
    }
}
