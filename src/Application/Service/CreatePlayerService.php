<?php

namespace App\Application\Service;

use App\Domain\Model\Player;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\GenderRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreatePlayerService
{
    private PlayerRepository $playerRepository;
    private GenderRepository $genderRepository;
    private ValidatorInterface $validator;

    public function __construct(PlayerRepository $playerRepository, GenderRepository $genderRepository, ValidatorInterface $validator)
    {
        $this->playerRepository = $playerRepository;
        $this->genderRepository = $genderRepository;
        $this->validator = $validator;
    }

    public function execute(string $fullName, int $habilityLevel, int $luckyLevel, int $genderId): Player
    {
        $gender = $this->genderRepository->find($genderId);

        if (!$gender) {
            throw new \InvalidArgumentException("Invalid gender ID");
        }

        $player = new Player();
        $player->setFullName($fullName);
        $player->setHabilityLevel($habilityLevel);
        $player->setLuckyLevel($luckyLevel);
        $player->setGender($gender);

        $errors = $this->validator->validate($player);

        if (count($errors) > 0) {
            throw new ValidationFailedException($player, $errors);
        }

        $this->playerRepository->save($player);

        return $player;
    }
}
