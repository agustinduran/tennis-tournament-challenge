<?php

namespace App\Tests\Application\Service;

use App\Application\Service\CreatePlayerService;
use App\Domain\Model\Player;
use App\Domain\Repository\PlayerRepository;
use PHPUnit\Framework\TestCase;

class CreatePlayerServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $playerRepository = $this->createMock(PlayerRepository::class);
        $playerRepository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Player::class));

        $service = new CreatePlayerService($playerRepository);

        $player = $service->execute('John Doe', 5, 3, 1);

        $this->assertInstanceOf(Player::class, $player);
        $this->assertEquals('John Doe', $player->getFullName());
        $this->assertEquals(5, $player->getHabilityLevel());
        $this->assertEquals(3, $player->getLuckyLevel());
    }
}
