<?php

namespace App\Domain\Service;

use App\Domain\Model\Player;
use App\Domain\Model\PlayerProperty;
use App\Domain\Model\PlayerPropertyValue;
use App\Domain\Repository\PlayerPropertyValueRepository;

class PlayerPropertyService
{
    private $playerPropertyValueRepository;

    public function __construct(PlayerPropertyValueRepository $playerPropertyValueRepository)
    {
        $this->playerPropertyValueRepository = $playerPropertyValueRepository;
    }

    public function addPropertyValue(Player $player, PlayerProperty $playerProperty, int $value): void
    {
        $existingPropertyValue = $this->playerPropertyValueRepository->findOneBy([
            'player' => $player,
            'playerProperty' => $playerProperty,
        ]);

        if ($existingPropertyValue !== null) {
            throw new \Exception('Jugador ya tiene esa propiedad asignada');
        }

        $playerPropertyValue = new PlayerPropertyValue();
        $playerPropertyValue->setPlayer($player);
        $playerPropertyValue->setProperty($playerProperty);
        $playerPropertyValue->setValue($value);

        $this->playerPropertyValueRepository->save($playerPropertyValue);
    }
}
