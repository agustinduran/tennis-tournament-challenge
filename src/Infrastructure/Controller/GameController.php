<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CreateGameService;
use App\Application\Service\GetGameService;
use App\Application\Service\GetGamesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class GameController extends AbstractController
{
    private CreateGameService $createGameService;
    private GetGameService $getGameService;
    private GetGamesService $getGamesService;

    public function __construct(
        CreateGameService $createGameService,
        GetGameService $getGameService,
        GetGamesService $getGamesService
    ) {
        $this->createGameService = $createGameService;
        $this->getGameService = $getGameService;
        $this->getGamesService = $getGamesService;
    }

    #[Route('/api/games', name: 'create_game', methods: ['POST'])]
    #[OA\Post(
        path: '/api/games',
        tags: ['Game'],
        summary: 'Create a new game',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'tournamentId', type: 'integer', example: 1),
                    new OA\Property(property: 'player1Id', type: 'integer', example: 1),
                    new OA\Property(property: 'player2Id', type: 'integer', example: 2),
                    new OA\Property(property: 'winnerId', type: 'integer', example: 3, nullable: true),
                    new OA\Property(property: 'nextGameId', type: 'integer', example: 4, nullable: true),
                    new OA\Property(property: 'stage', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Returns the created game',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'tournament', ref: '#/components/schemas/Tournament'),
                        new OA\Property(property: 'player1', ref: '#/components/schemas/Player', nullable: true),
                        new OA\Property(property: 'player2', ref: '#/components/schemas/Player', nullable: true),
                        new OA\Property(property: 'winner', ref: '#/components/schemas/Player', nullable: true),
                        new OA\Property(property: 'nextGameId', type: 'integer', nullable: true),
                        new OA\Property(property: 'stage', type: 'integer')
                    ]
                )
            )
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $game = $this->createGameService->execute(
                $data['tournamentId'],
                $data['player1Id'],
                $data['player2Id'],
                $data['winnerId'] ?? null,
                $data['nextGameId'] ?? null,
                $data['stage']
            );

            return new JsonResponse([
                'id' => $game->getId(),
                'tournament' => [
                    'id' => $game->getTournament()->getId(),
                    'title' => $game->getTournament()->getTitle()
                ],
                'player1' => $game->getPlayer1() ? [
                        'id' => $game->getPlayer1()->getId(),
                        'name' => $game->getPlayer1()->getFullname(),
                        'habilityLevel' => $game->getPlayer1()->getHabilityLevel(),
                        'luckyLevel' => $game->getPlayer1()->getLuckyLevel()
                    ] : null,
                'player2' => $game->getPlayer2() ? [
                    'id' => $game->getPlayer2()->getId(),
                    'name' => $game->getPlayer2()->getFullname(),
                    'habilityLevel' => $game->getPlayer2()->getHabilityLevel(),
                    'luckyLevel' => $game->getPlayer2()->getLuckyLevel()
                ] : null,
                'winner' => $game->getWinner() ? [
                    'id' => $game->getWinner()->getId(),
                    'name' => $game->getWinner()->getFullname()
                ] : null,
                'nextGameId' => $game->getNextGame() ? $game->getNextGame()->getId() : null,
                'stage' => $game->getStage(),
            ], JsonResponse::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/games', name: 'get_games', methods: ['GET'])]
    #[OA\Get(
        path: '/api/games',
        summary: 'Get all games',
        tags: ['Game'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of games',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'tournament', ref: '#/components/schemas/Tournament'),
                            new OA\Property(property: 'player1', ref: '#/components/schemas/Player', nullable: true),
                            new OA\Property(property: 'player2', ref: '#/components/schemas/Player', nullable: true),
                            new OA\Property(property: 'winner', ref: '#/components/schemas/Player', nullable: true),
                            new OA\Property(property: 'nextGameId', type: 'integer', nullable: true),
                            new OA\Property(property: 'stage', type: 'integer')
                        ]
                    )
                )
            )
        ]
    )]
    public function getGames(): JsonResponse
    {
        $games = $this->getGamesService->execute();

        $data = array_map(function($game) {
            return [
                'id' => $game->getId(),
                'tournament' => [
                    'id' => $game->getTournament()->getId(),
                    'title' => $game->getTournament()->getTitle()
                ],
                'player1' => $game->getPlayer1() ? [
                        'id' => $game->getPlayer1()->getId(),
                        'name' => $game->getPlayer1()->getFullname(),
                        'habilityLevel' => $game->getPlayer1()->getHabilityLevel(),
                        'luckyLevel' => $game->getPlayer1()->getLuckyLevel()
                    ] : null,
                'player2' => $game->getPlayer2() ? [
                    'id' => $game->getPlayer2()->getId(),
                    'name' => $game->getPlayer2()->getFullname(),
                    'habilityLevel' => $game->getPlayer2()->getHabilityLevel(),
                    'luckyLevel' => $game->getPlayer2()->getLuckyLevel()
                ] : null,
                'winner' => $game->getWinner() ? [
                    'id' => $game->getWinner()->getId(),
                    'name' => $game->getWinner()->getFullname()
                ] : null,
                'nextGameId' => $game->getNextGame() ? $game->getNextGame()->getId() : null,
                'stage' => $game->getStage(),
            ];
        }, $games);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/games/{id}', name: 'get_game', methods: ['GET'])]
    #[OA\Get(
        path: '/api/games/{id}',
        summary: 'Get a game by ID',
        tags: ['Game'],
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
                description: 'Returns the game data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'tournament', ref: '#/components/schemas/Tournament'),
                        new OA\Property(property: 'player1', ref: '#/components/schemas/Player', nullable: true),
                        new OA\Property(property: 'player2', ref: '#/components/schemas/Player', nullable: true),
                        new OA\Property(property: 'winner', ref: '#/components/schemas/Player', nullable: true),
                        new OA\Property(property: 'nextGameId', type: 'integer', nullable: true),
                        new OA\Property(property: 'stage', type: 'integer')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Game not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Game not found')
                    ]
                )
            )
        ]
    )]
    public function getGame(int $id): JsonResponse
    {
        $game = $this->getGameService->execute($id);

        if (!$game) {
            return new JsonResponse(['error' => 'Game not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $game->getId(),
            'tournament' => [
                'id' => $game->getTournament()->getId(),
                'title' => $game->getTournament()->getTitle()
            ],
            'player1' => $game->getPlayer1() ? [
                    'id' => $game->getPlayer1()->getId(),
                    'name' => $game->getPlayer1()->getFullname(),
                    'habilityLevel' => $game->getPlayer1()->getHabilityLevel(),
                    'luckyLevel' => $game->getPlayer1()->getLuckyLevel()
                ] : null,
            'player2' => $game->getPlayer2() ? [
                'id' => $game->getPlayer2()->getId(),
                'name' => $game->getPlayer2()->getFullname(),
                'habilityLevel' => $game->getPlayer2()->getHabilityLevel(),
                'luckyLevel' => $game->getPlayer2()->getLuckyLevel()
            ] : null,
            'winner' => $game->getWinner() ? [
                'id' => $game->getWinner()->getId(),
                'name' => $game->getWinner()->getFullname()
            ] : null,
            'nextGameId' => $game->getNextGame() ? $game->getNextGame()->getId() : null,
            'stage' => $game->getStage(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
