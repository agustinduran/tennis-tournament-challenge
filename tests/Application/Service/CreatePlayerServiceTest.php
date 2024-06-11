<?php

namespace App\Tests\Application\Service;

use App\Application\Service\CreatePlayerService;
use App\Domain\Model\Player;
use App\Domain\Model\Gender;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\GenderRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class CreatePlayerServiceTest extends TestCase
{
    private $playerRepository;
    private $genderRepository;
    private $validator;
    private $createPlayerService;

    protected function setUp(): void
    {
        $this->playerRepository = $this->createMock(PlayerRepository::class);
        $this->genderRepository = $this->createMock(GenderRepository::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->createPlayerService = new CreatePlayerService(
            $this->playerRepository,
            $this->genderRepository,
            $this->validator
        );
    }

    public function testExecute(): void
    {
        $gender = new Gender();
        $reflectionGender = new \ReflectionClass(Gender::class);
        $propertyGender = $reflectionGender->getProperty('id');
        $propertyGender->setAccessible(true);
        $propertyGender->setValue($gender, 1);

        $this->genderRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($gender);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(new ConstraintViolationList());

        $this->playerRepository
            ->expects($this->once())
            ->method('save');

        $player = $this->createPlayerService->execute('Rafael Nadal', 80, 60, 1);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('Rafael Nadal', $player->getFullName());
        $this->assertEquals(80, $player->getHabilityLevel());
        $this->assertEquals(60, $player->getLuckyLevel());
        $this->assertEquals($gender, $player->getGender());
    }

    public function testExecuteValidationFailed(): void
    {
        $gender = new Gender();
        $reflectionGender = new \ReflectionClass(Gender::class);
        $propertyGender = $reflectionGender->getProperty('id');
        $propertyGender->setAccessible(true);
        $propertyGender->setValue($gender, 1);

        $this->genderRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($gender);

        $violations = new ConstraintViolationList([
            // Simulate validation errors
        ]);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($violations);

        $this->expectException(ValidationFailedException::class);

        $this->createPlayerService->execute('Rafael Nadal', 80, 60, 1);
    }

    public function testExecuteGenderNotFound(): void
    {
        $this->genderRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Gender not found');

        $this->createPlayerService->execute('Rafael Nadal', 80, 60, 1);
    }
}
