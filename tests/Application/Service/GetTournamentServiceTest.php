<?php

namespace App\Tests\Application\Service;

use App\Application\Service\GetTournamentService;
use App\Domain\Model\Tournament;
use App\Domain\Repository\TournamentRepository;
use PHPUnit\Framework\TestCase;

class GetTournamentServiceTest extends TestCase
{
    public function testExecute(): void
    {
        $tournamentRepository = $this->createMock(TournamentRepository::class);
        $getTournamentService = new GetTournamentService($tournamentRepository);

        $tournament = new Tournament();
        $reflection = new \ReflectionClass($tournament);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($tournament, 1);

        $tournamentRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($tournament);

        $result = $getTournamentService->execute(1);
        $this->assertSame($tournament, $result);
    }
}
