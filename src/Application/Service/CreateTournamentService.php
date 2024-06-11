<?php

namespace App\Application\Service;

use App\Domain\Model\Tournament;
use App\Domain\Repository\GenderRepository;
use App\Domain\Repository\TournamentRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreateTournamentService
{
    private TournamentRepository $tournamentRepository;
    private GenderRepository $genderRepository;
    private ValidatorInterface $validator;

    public function __construct(TournamentRepository $tournamentRepository, GenderRepository $genderRepository, ValidatorInterface $validator)
    {
        $this->tournamentRepository = $tournamentRepository;
        $this->genderRepository = $genderRepository;
        $this->validator = $validator;
    }

    public function execute(string $title, \DateTimeInterface $date, int $genderId): Tournament
    {
        $gender = $this->genderRepository->find($genderId);

        if (!$gender) {
            throw new \InvalidArgumentException("Invalid gender ID");
        }

        $tournament = new Tournament();
        $tournament->setTitle($title);
        $tournament->setDate($date);
        $tournament->setGender($gender);

        $errors = $this->validator->validate($tournament);

        if (count($errors) > 0) {
            throw new ValidationFailedException($tournament, $errors);
        }

        $this->tournamentRepository->save($tournament);

        return $tournament;
    }
}
