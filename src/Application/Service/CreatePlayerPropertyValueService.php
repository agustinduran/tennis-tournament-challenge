<?php

namespace App\Application\Service;

use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerRepository;
use App\Domain\Repository\PlayerPropertyRepository;
use App\Domain\Repository\PlayerPropertyValueRepository;
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
        $property = $this->playerPropertyRepository->find($propertyId);

        if (!$player || !$property) {
            throw new \InvalidArgumentException("Invalid player or property ID");
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
