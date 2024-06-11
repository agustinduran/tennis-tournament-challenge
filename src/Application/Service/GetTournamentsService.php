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

    public function execute(?string $date = null, ?int $genderId = null): array
    {
        return $this->tournamentRepository->findByFilters($date, $genderId);
    }
}
