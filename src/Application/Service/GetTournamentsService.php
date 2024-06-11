<?php

namespace App\Application\Service;

use App\Domain\Model\Tournament;
use App\Domain\Repository\TournamentRepository;

class GetTournamentsService
{
    private TournamentRepository $tournamentRepository;

    public function __construct(TournamentRepository $tournamentRepository)
    {
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * @return Tournament[]
     */
    public function execute(): array
    {
        return $this->tournamentRepository->findAll();
    }
}
