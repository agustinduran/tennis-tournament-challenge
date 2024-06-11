<?php

namespace App\Application\Service;

use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerPropertyValueRepository;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\PlayerPropertyRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CreatePlayerPropertyValueService
{
    private PlayerPropertyValueRepository $playerPropertyValueRepository;
    private PlayerRepository $playerRepository;
    private PlayerPropertyRepository $playerPropertyRepository;
    private ValidatorInterface $validator;

    public function __construct(
        PlayerPropertyValueRepository $playerPropertyValueRepository,
        PlayerRepository $playerRepository,
        PlayerPropertyRepository $playerPropertyRepository,
        ValidatorInterface $validator
    ) {
        $this->playerPropertyValueRepository = $playerPropertyValueRepository;
        $this->playerRepository = $playerRepository;
        $this->playerPropertyRepository = $playerPropertyRepository;
        $this->validator = $validator;
    }

    public function execute(int $playerId, int $propertyId, int $value): PlayerPropertyValue
    {
        $player = $this->playerRepository->find($playerId);
        if (!$player) {
            throw new \InvalidArgumentException('Invalid player ID');
        }

        $property = $this->playerPropertyRepository->find($propertyId);
        if (!$property) {
            throw new \InvalidArgumentException('Invalid property ID');
        }

        if ($property->getGender() != $player->getGender()) {
            throw new \InvalidArgumentException('Invalid property for player with id $player->getId(). Gender doesn\'t match');
        }

        $playerPropertyValue = new PlayerPropertyValue();
        $playerPropertyValue->setPlayer($player);
        $playerPropertyValue->setProperty($property);
        $playerPropertyValue->setValue($value);

        $errors = $this->validator->validate($playerPropertyValue);

        if (count($errors) > 0) {
            throw new ValidationFailedException($playerPropertyValue, $errors);
        }

        $this->playerPropertyValueRepository->save($playerPropertyValue);

        return $playerPropertyValue;
    }
}
