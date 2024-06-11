<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\GetPlayerPropertiesService;
use App\Application\Service\GetPlayerPropertyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class PlayerPropertyController extends AbstractController
{
    private GetPlayerPropertiesService $getPlayerPropertiesService;
    private GetPlayerPropertyService $getPlayerPropertyService;

    public function __construct(
        GetPlayerPropertiesService $getPlayerPropertiesService,
        GetPlayerPropertyService $getPlayerPropertyService
    )
    {
        $this->getPlayerPropertiesService = $getPlayerPropertiesService;
        $this->getPlayerPropertyService = $getPlayerPropertyService;
    }

    #[Route('/api/player-properties', name: 'get_player_properties', methods: ['GET'])]
    #[OA\Get(
        path: '/api/player-properties',
        summary: 'Get all player properties',
        tags: ['PlayerProperty'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of player properties',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'name', type: 'string')
                        ]
                    )
                )
            )
        ]
    )]
    public function getPlayerProperties(): JsonResponse
    {
        $playerProperties = $this->getPlayerPropertiesService->execute();

        $data = array_map(function($playerProperty) {
            return [
                'id' => $playerProperty->getId(),
                'name' => $playerProperty->getName(),
            ];
        }, $playerProperties);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/player-properties/{id}', name: 'get_player_property', methods: ['GET'])]
    #[OA\Get(
        path: '/api/player-properties/{id}',
        summary: 'Get a player property by ID',
        tags: ['PlayerProperty'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the player property data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'name', type: 'string')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Player property not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Player property not found')
                    ]
                )
            )
        ]
    )]
    public function getPlayerProperty(int $id): JsonResponse
    {
        $playerProperty = $this->getPlayerPropertyService->execute($id);

        if (!$playerProperty) {
            return new JsonResponse(['error' => 'Player property not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $playerProperty->getId(),
            'name' => $playerProperty->getName(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
