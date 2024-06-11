<?php

namespace App\Application\Service;

use App\Domain\Model\Player;
use App\Domain\Repository\PlayerRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreatePlayerService
{
    private PlayerRepository $playerRepository;
    private ValidatorInterface $validator;

    public function __construct(PlayerRepository $playerRepository, ValidatorInterface $validator)
    {
        $this->playerRepository = $playerRepository;
        $this->validator = $validator;
    }

    public function execute(string $fullName, int $habilityLevel, int $luckyLevel, int $genderId): Player
    {
        $player = new Player();
        $player->setFullName($fullName);
        $player->setHabilityLevel($habilityLevel);
        $player->setLuckyLevel($luckyLevel);
        // Necesitarías cargar la entidad Gender por su ID aquí

        $errors = $this->validator->validate($player);

        if (count($errors) > 0) {
            throw new ValidationFailedException($player, $errors);
        }

        $this->playerRepository->save($player);

        return $player;
    }
}
