<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\CreateTournamentService;
use App\Application\Service\GetTournamentService;
use App\Application\Service\GetTournamentsService;
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

    public function __construct(
        CreateTournamentService $createTournamentService,
        GetTournamentService $getTournamentService,
        GetTournamentsService $getTournamentsService
    ) {
        $this->createTournamentService = $createTournamentService;
        $this->getTournamentService = $getTournamentService;
        $this->getTournamentsService = $getTournamentsService;
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
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2023-05-28'),
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
                        new OA\Property(property: 'genderId', type: 'integer')
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
                'genderId' => $tournament->getGender()->getId(),
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
                            new OA\Property(property: 'genderId', type: 'integer')
                        ]
                    )
                )
            )
        ]
    )]
    public function getTournaments(): JsonResponse
    {
        $tournaments = $this->getTournamentsService->execute();

        $data = array_map(function($tournament) {
            return [
                'id' => $tournament->getId(),
                'title' => $tournament->getTitle(),
                'date' => $tournament->getDate()->format('Y-m-d'),
                'genderId' => $tournament->getGender()->getId(),
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
                        new OA\Property(property: 'genderId', type: 'integer')
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
            'genderId' => $tournament->getGender()->getId(),
        ];

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}