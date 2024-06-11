<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CreatePlayerPropertyValueService;
use App\Application\Service\GetPlayerPropertyValuesService;
use App\Application\Service\GetPlayerPropertyValueService;
use App\Application\Service\GetPlayerPropertyValuesByPlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class PlayerPropertyValueController extends AbstractController
{
    private CreatePlayerPropertyValueService $createPlayerPropertyValueService;
    private GetPlayerPropertyValuesService $getPlayerPropertyValuesService;
    private GetPlayerPropertyValueService $getPlayerPropertyValueService;
    private GetPlayerPropertyValuesByPlayerService $getPlayerPropertyValuesByPlayerService;

    public function __construct(
        CreatePlayerPropertyValueService $createPlayerPropertyValueService,
        GetPlayerPropertyValuesService $getPlayerPropertyValuesService,
        GetPlayerPropertyValueService $getPlayerPropertyValueService,
        GetPlayerPropertyValuesByPlayerService $getPlayerPropertyValuesByPlayerService
    ) {
        $this->createPlayerPropertyValueService = $createPlayerPropertyValueService;
        $this->getPlayerPropertyValuesService = $getPlayerPropertyValuesService;
        $this->getPlayerPropertyValueService = $getPlayerPropertyValueService;
        $this->getPlayerPropertyValuesByPlayerService = $getPlayerPropertyValuesByPlayerService;
    }

    #[Route('/api/player-property-values', name: 'create_player_property_value', methods: ['POST'])]
    #[OA\Post(
        path: '/api/player-property-values',
        tags: ['PlayerPropertyValue'],
        summary: 'Create a new player property value',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'playerId', type: 'integer', example: 1),
                    new OA\Property(property: 'propertyId', type: 'integer', example: 1),
                    new OA\Property(property: 'value', type: 'integer', example: 80),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Returns the created player property value',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'player', ref: '#/components/schemas/Player'),
                        new OA\Property(property: 'property', ref: '#/components/schemas/PlayerProperty'),
                        new OA\Property(property: 'value', type: 'integer'),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $createdPlayerPropertyValue = $this->createPlayerPropertyValueService->execute(
                $data['playerId'],
                $data['propertyId'],
                $data['value']
            );

            return new JsonResponse([
                'id' => $createdPlayerPropertyValue->getId(),
                'player' => [
                    'id' => $createdPlayerPropertyValue->getPlayer()->getId(),
                    'fullName' => $createdPlayerPropertyValue->getPlayer()->getFullName(),
                ],
                'property' => [
                    'id' => $createdPlayerPropertyValue->getProperty()->getId(),
                    'name' => $createdPlayerPropertyValue->getProperty()->getName(),
                ],
                'value' => $createdPlayerPropertyValue->getValue(),
            ], JsonResponse::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['errors' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/player-property-values', name: 'get_player_property_values', methods: ['GET'])]
    #[OA\Get(
        path: '/api/player-property-values',
        summary: 'Get all player property values',
        tags: ['PlayerPropertyValue'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of player property values',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'player', ref: '#/components/schemas/Player'),
                            new OA\Property(property: 'property', ref: '#/components/schemas/PlayerProperty'),
                            new OA\Property(property: 'value', type: 'integer'),
                        ]
                    )
                )
            )
        ]
    )]
    public function getPlayerPropertyValues(): JsonResponse
    {
        $playerPropertyValues = $this->getPlayerPropertyValuesService->execute();

        $data = array_map(function ($playerPropertyValue) {
            return [
                'id' => $playerPropertyValue->getId(),
                'player' => [
                    'id' => $playerPropertyValue->getPlayer()->getId(),
                    'fullName' => $playerPropertyValue->getPlayer()->getFullName(),
                ],
                'property' => [
                    'id' => $playerPropertyValue->getProperty()->getId(),
                    'name' => $playerPropertyValue->getProperty()->getName(),
                ],
                'value' => $playerPropertyValue->getValue(),
            ];
        }, $playerPropertyValues);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/player-property-values/{id}', name: 'get_player_property_value', methods: ['GET'])]
    #[OA\Get(
        path: '/api/player-property-values/{id}',
        summary: 'Get a player property value by ID',
        tags: ['PlayerPropertyValue'],
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
                description: 'Returns the player property value data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'player', ref: '#/components/schemas/Player'),
                        new OA\Property(property: 'property', ref: '#/components/schemas/PlayerProperty'),
                        new OA\Property(property: 'value', type: 'integer'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Player property value not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Player property value not found')
                    ]
                )
            )
        ]
    )]
    public function getPlayerPropertyValue(int $id): JsonResponse
    {
        $playerPropertyValue = $this->getPlayerPropertyValueService->execute($id);

        if (!$playerPropertyValue) {
            return new JsonResponse(['error' => 'Player property value not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $playerPropertyValue->getId(),
            'player' => [
                'id' => $playerPropertyValue->getPlayer()->getId(),
                'fullName' => $playerPropertyValue->getPlayer()->getFullName(),
            ],
            'property' => [
                'id' => $playerPropertyValue->getProperty()->getId(),
                'name' => $playerPropertyValue->getProperty()->getName(),
            ],
            'value' => $playerPropertyValue->getValue(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/player-property-values/player/{playerId}', name: 'get_player_property_values_by_player', methods: ['GET'])]
    #[OA\Get(
        path: '/api/player-property-values/player/{playerId}',
        summary: 'Get all player property values for a specific player',
        tags: ['PlayerPropertyValue'],
        parameters: [
            new OA\Parameter(
                name: 'playerId',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of player property values for a specific player',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'player', ref: '#/components/schemas/Player'),
                            new OA\Property(property: 'property', ref: '#/components/schemas/PlayerProperty'),
                            new OA\Property(property: 'value', type: 'integer'),
                        ]
                    )
                )
            )
        ]
    )]
    public function getPlayerPropertyValuesByPlayer(int $playerId): JsonResponse
    {
        $playerPropertyValues = $this->getPlayerPropertyValuesByPlayerService->execute($playerId);

        $data = array_map(function ($playerPropertyValue) {
            return [
                'id' => $playerPropertyValue->getId(),
                'player' => [
                    'id' => $playerPropertyValue->getPlayer()->getId(),
                    'fullName' => $playerPropertyValue->getPlayer()->getFullName(),
                ],
                'property' => [
                    'id' => $playerPropertyValue->getProperty()->getId(),
                    'name' => $playerPropertyValue->getProperty()->getName(),
                ],
                'value' => $playerPropertyValue->getValue(),
            ];
        }, $playerPropertyValues);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
