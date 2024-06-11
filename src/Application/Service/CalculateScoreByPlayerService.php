<?php

namespace App\Application\Service;

use App\Domain\Model\Player;
use App\Domain\Repository\PlayerPropertyValueRepository;

class CalculateScoreByPlayerService
{
    private PlayerPropertyValueRepository $playerPropertyValueRepository;

    public function __construct(PlayerPropertyValueRepository $playerPropertyValueRepository)
    {
        $this->playerPropertyValueRepository = $playerPropertyValueRepository;
    }

    public function execute(Player $player): int
    {
        $habilityLevel = $player->getHabilityLevel();
        $luckyLevel = $player->getLuckyLevel();

        $propertyValues = $this->playerPropertyValueRepository->findByPlayerId($player->getId());
        $propertyScore = array_reduce($propertyValues, function ($carry, $item) {
            return $carry + $item->getValue();
        }, 0);

        $randomFactor = rand(0, 10);

        $totalScore = ($habilityLevel + $luckyLevel) * 0.8 + $propertyScore * 0.2 + $randomFactor * 0.05;

        return (int)$totalScore;
    }
}
