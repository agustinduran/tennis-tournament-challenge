<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CreateTournamentService;
use App\Application\Service\GetTournamentService;
use App\Application\Service\GetTournamentsService;
use App\Application\Service\GenerateGamesService;
use App\Application\Service\RandomizeTournamentResultService;
use App\Application\Service\GetPlayerWinnerTournamentService;
use App\Application\Service\GetTournamentBracketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class TournamentController extends AbstractController
{
    private CreateTournamentService $createTournamentService;
    private GetTournamentService $getTournamentService;
    private GetTournamentsService $getTournamentsService;
    private GenerateGamesService $generateGamesService;
    private RandomizeTournamentResultService $randomizeTournamentResultService;
    private GetPlayerWinnerTournamentService $getPlayerWinnerTournamentService;
    private GetTournamentBracketService $getTournamentBracketService;

    public function __construct(
        CreateTournamentService $createTournamentService,
        GetTournamentService $getTournamentService,
        GetTournamentsService $getTournamentsService,
        GenerateGamesService $generateGamesService,
        RandomizeTournamentResultService $randomizeTournamentResultService,
        GetPlayerWinnerTournamentService $getPlayerWinnerTournamentService,
        GetTournamentBracketService $getTournamentBracketService
    ) {
        $this->createTournamentService = $createTournamentService;
        $this->getTournamentService = $getTournamentService;
        $this->getTournamentsService = $getTournamentsService;
        $this->generateGamesService = $generateGamesService;
        $this->randomizeTournamentResultService = $randomizeTournamentResultService;
        $this->getPlayerWinnerTournamentService = $getPlayerWinnerTournamentService;
        $this->getTournamentBracketService = $getTournamentBracketService;
    }

    #[Route('/api/tournaments', name: 'create_tournament', methods: ['POST'])]
    #[OA\Post(
        path: '/api/tournaments',
        tags: ['Tournament'],
        summary: 'Create a new tournament',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Roland Garros'),
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2024-06-10'),
                    new OA\Property(property: 'genderId', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Returns the created tournament',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'title', type: 'string'),
                        new OA\Property(property: 'date', type: 'string', format: 'date'),
                        new OA\Property(property: 'gender', ref: '#/components/schemas/Gender')
                    ]
                )
            )
        ]
    )]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $tournament = $this->createTournamentService->execute(
                $data['title'],
                new \DateTime($data['date']),
                $data['genderId']
            );

            return new JsonResponse([
                'id' => $tournament->getId(),
                'title' => $tournament->getTitle(),
                'date' => $tournament->getDate()->format('Y-m-d'),
                'gender' => [
                    'id' => $tournament->getGender()->getId(),
                    'name' => $tournament->getGender()->getName()
                ]
            ], JsonResponse::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/tournaments', name: 'get_tournaments', methods: ['GET'])]
    #[OA\Get(
        path: '/api/tournaments',
        summary: 'Get all tournaments',
        tags: ['Tournament'],
        parameters: [
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date'),
                description: 'Filter tournaments by date (format: YYYY-MM-DD)'
            ),
            new OA\Parameter(
                name: 'genderId',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
                description: 'Filter tournaments by gender ID'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of tournaments',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer'),
                            new OA\Property(property: 'title', type: 'string'),
                            new OA\Property(property: 'date', type: 'string', format: 'date'),
                            new OA\Property(property: 'gender', ref: '#/components/schemas/Gender')
                        ]
                    )
                )
            )
        ]
    )]
    public function getTournaments(Request $request): JsonResponse
    {
        $date = $request->query->get('date');
        $genderId = $request->query->get('genderId');
        
        $tournaments = $this->getTournamentsService->execute($date, $genderId);

        $data = array_map(function($tournament) {
            return [
                'id' => $tournament->getId(),
                'title' => $tournament->getTitle(),
                'date' => $tournament->getDate()->format('Y-m-d'),
                'gender' => [
                    'id' => $tournament->getGender()->getId(),
                    'name' => $tournament->getGender()->getName()
                ]
            ];
        }, $tournaments);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/tournaments/{id}', name: 'get_tournament', methods: ['GET'])]
    #[OA\Get(
        path: '/api/tournaments/{id}',
        summary: 'Get a tournament by ID',
        tags: ['Tournament'],
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
                description: 'Returns the tournament data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer'),
                        new OA\Property(property: 'title', type: 'string'),
                        new OA\Property(property: 'date', type: 'string', format: 'date'),
                        new OA\Property(property: 'gender', ref: '#/components/schemas/Gender')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Tournament not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Tournament not found')
                    ]
                )
            )
        ]
    )]
    public function getTournament(int $id): JsonResponse
    {
        $tournament = $this->getTournamentService->execute($id);

        if (!$tournament) {
            return new JsonResponse(['error' => 'Tournament not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = [
            'id' => $tournament->getId(),
            'title' => $tournament->getTitle(),
            'date' => $tournament->getDate()->format('Y-m-d'),
            'gender' => [
                'id' => $tournament->getGender()->getId(),
                'name' => $tournament->getGender()->getName()
            ]
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('/api/tournaments/{id}/generate-games', name: 'generate_games', methods: ['POST'])]
    #[OA\Post(
        path: '/api/tournaments/{id}/generate-games',
        tags: ['Tournament'],
        summary: 'Generate games for a tournament',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'playerIds', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3, 4, 5, 6, 7, 8])
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Games generated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Games generated successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Player count must be a power of 2 and at least 2.')
                    ]
                )
            )
        ]
    )]
    public function generateGames(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $tournament = $this->getTournamentService->execute($id);
            
            if (!$tournament) {
                return new JsonResponse(['error' => 'Tournament not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $this->generateGamesService->execute($tournament, $data['playerIds']);

            return new JsonResponse(['message' => 'Games generated successfully'], JsonResponse::HTTP_CREATED);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/tournaments/{id}/simulate', name: 'simulate_tournament', methods: ['POST'])]
    #[OA\Post(
        path: '/api/tournaments/{id}/simulate',
        tags: ['Tournament'],
        summary: 'Simulate a tournament and get the final winner. The games needs to be generated before',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tournament simulated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'winner', type: 'object', 
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'fullName', type: 'string'),
                            ]
                        ),
                        new OA\Property(
                            property: 'bracket',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'stage', type: 'integer'),
                                    new OA\Property(property: 'gameId', type: 'integer'),
                                    new OA\Property(property: 'player1', type: 'string'),
                                    new OA\Property(property: 'player2', type: 'string'),
                                    new OA\Property(property: 'winner', type: 'string'),
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Player count must be a power of 2 and at least 2.')
                    ]
                )
            )
        ]
    )]
    public function simulateTournament(int $id, Request $request): JsonResponse
    {
        try {
            $tournament = $this->getTournamentService->execute($id);

            if (!$tournament) {
                return new JsonResponse(['error' => 'Tournament not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            $this->randomizeTournamentResultService->execute($tournament);

            $winner = $this->getPlayerWinnerTournamentService->execute($id);

            $finalBracket = $this->getTournamentBracketService->execute($id);

            return new JsonResponse([
                'winner' => [
                    'id' => $winner->getId(),
                    'fullName' => $winner->getFullName()
                ],
                'bracket' => $finalBracket
            ], JsonResponse::HTTP_OK);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/tournaments/{id}/simulate-complete', name: 'simulate_complete_tournament', methods: ['POST'])]
    #[OA\Post(
        path: '/api/tournaments/{id}/simulate-complete',
        tags: ['Tournament'],
        summary: 'Simulate a complete tournament making and randomize the games and get the final winner',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'playerIds', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2, 3, 4, 5, 6, 7, 8])
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tournament simulated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'winner', type: 'object', 
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'fullName', type: 'string'),
                            ]
                        ),
                        new OA\Property(
                            property: 'bracket',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'stage', type: 'integer'),
                                    new OA\Property(property: 'gameId', type: 'integer'),
                                    new OA\Property(property: 'player1', type: 'string'),
                                    new OA\Property(property: 'player2', type: 'string'),
                                    new OA\Property(property: 'winner', type: 'string'),
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid input',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'Player count must be a power of 2 and at least 2.')
                    ]
                )
            )
        ]
    )]
    public function simulateCompleteTournament(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $tournament = $this->getTournamentService->execute($id);

            if (!$tournament) {
                return new JsonResponse(['error' => 'Tournament not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            if (!$data) {
                return new JsonResponse(['error' => 'Invalid input'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $this->generateGamesService->execute($tournament, $data['playerIds']);

            $this->randomizeTournamentResultService->execute($tournament);

            $winner = $this->getPlayerWinnerTournamentService->execute($id);

            $finalBracket = $this->getTournamentBracketService->execute($id);

            return new JsonResponse([
                'winner' => [
                    'id' => $winner->getId(),
                    'fullName' => $winner->getFullName()
                ],
                'bracket' => $finalBracket
            ], JsonResponse::HTTP_OK);
        } catch (ValidationFailedException $e) {
            $errors = [];
            foreach ($e->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/tournaments/{id}/winner', name: 'get_tournament_winner', methods: ['GET'])]
    #[OA\Get(
        path: '/api/tournaments/{id}/winner',
        summary: 'Get the winner of the tournament',
        tags: ['Tournament'],
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
                description: 'Returns the winner of the tournament',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'winner', type: 'object', 
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'fullName', type: 'string'),
                            ]
                        ),
                        new OA\Property(
                            property: 'bracket',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'stage', type: 'integer'),
                                    new OA\Property(property: 'gameId', type: 'integer'),
                                    new OA\Property(property: 'player1', type: 'string'),
                                    new OA\Property(property: 'player2', type: 'string'),
                                    new OA\Property(property: 'winner', type: 'string'),
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'No winner found for the tournament',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'error', type: 'string', example: 'No winner found for the tournament')
                    ]
                )
            )
        ]
    )]
    public function getTournamentWinner(int $id): JsonResponse
    {
        try {

            $winner = $this->getPlayerWinnerTournamentService->execute($id);
            $finalBracket = $this->getTournamentBracketService->execute($id);

            return new JsonResponse([
                'winner' => [
                    'id' => $winner->getId(),
                    'fullName' => $winner->getFullName()
                ],
                'bracket' => $finalBracket
            ], JsonResponse::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
