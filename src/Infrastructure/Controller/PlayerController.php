<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CreatePlayerService;
use App\Application\Service\GetPlayersService;
use App\Application\Service\GetPlayerService;
use App\Domain\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

class PlayerController extends AbstractController
{
    private CreatePlayerService $createPlayerService;
    private GetPlayersService $getPlayersService;
    private GetPlayerService $getPlayerService;

    public function __construct(
        CreatePlayerService $createPlayerService,
        GetPlayersService $getPlayersService,
        GetPlayerService $getPlayerService
    )
    {
        $this->createPlayerService = $createPlayerService;
        $this->getPlayersService = $getPlayersService;
        $this->getPlayerService = $getPlayerService;
    }

    #[Route('/api/players', name: 'create_player', methods: ['POST'])]
    #[OA\Post(
        path: '/api/players',
        tags: ['Player'],
        summary: 'Create a new player',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'fullName', type: 'string', example: 'Pico Mónaco'),
                    new OA\Property(property: 'habilityLevel', type: 'integer', example: 80),
                    new OA\Property(property: 'luckyLevel', type: 'integer', example: 60),
                    new OA\Property(property: 'genderId', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Returns the created player',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'fullName', type: 'string'),
                        new OA\Property(property: 'habilityLevel', type: 'integer'),
                        new OA\Property(property: 'luckyLevel', type: 'integer'),
                        new OA\Property(property: 'genderId', type: 'integer')
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $player = $this->createPlayerService->execute(
                $data['fullName'],
                $data['habilityLevel'],
                $data['luckyLevel'],
                $data['genderId']
            );

            return new JsonResponse([
                'id' => $player->getId(),
                'fullName' => $player->getFullName(),
                'habilityLevel' => $player->getHabilityLevel(),
                'luckyLevel' => $player->getLuckyLevel(),
                'genderId' => $player->getGender()->getId(),
            ], JsonResponse::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/players', name: 'get_players', methods: ['GET'])]
    #[OA\Get(
        path: '/api/players',
        summary: 'Get all players',
        tags: ['Player'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of players',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'fullName', type: 'string'),
                            new OA\Property(property: 'habilityLevel', type: 'integer'),
                            new OA\Property(property: 'luckyLevel', type: 'integer'),
                            new OA\Property(property: 'genderId', type: 'integer')
                        ]
                    )
                )
            )
        ]
    )]
    public function getPlayers(): JsonResponse
    {
        $players = $this->getPlayersService->execute();

        $data = array_map(function($player) {
            return [
                'id' => $player->getId(),
                'fullName' => $player->getFullName(),
                'habilityLevel' => $player->getHabilityLevel(),
                'luckyLevel' => $player->getLuckyLevel(),
                'genderId' => $player->getGender()->getId(),
            ];
        }, $players);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/players/{id}', name: 'get_player', methods: ['GET'])]
    #[OA\Get(
        path: '/api/players/{id}',
        summary: 'Get a player by ID',
        tags: ['Player'],
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
                description: 'Returns the player data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'fullName', type: 'string'),
                        new OA\Property(property: 'habilityLevel', type: 'integer'),
                        new OA\Property(property: 'luckyLevel', type: 'integer'),
                        new OA\Property(property: 'genderId', type: 'integer')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Player not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Player not found')
                    ]
                )
            )
        ]
    )]

    public function getPlayer(int $id): JsonResponse
    {
        $player = $this->getPlayerService->execute($id);

        if (!$player) {
            return new JsonResponse(['error' => 'Player not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $player->getId(),
            'fullName' => $player->getFullName(),
            'habilityLevel' => $player->getHabilityLevel(),
            'luckyLevel' => $player->getLuckyLevel(),
            'genderId' => $player->getGender()->getId(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
