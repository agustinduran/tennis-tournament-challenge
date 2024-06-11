<?php

namespace App\Application\Service;

use App\Domain\Model\Tournament;
use App\Domain\Repository\TournamentRepository;

class GetTournamentService
{
    private TournamentRepository $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    public function execute(int $id): ?Tournament
    {
        return $this->tournamentRepository->find($id);
    }
}
