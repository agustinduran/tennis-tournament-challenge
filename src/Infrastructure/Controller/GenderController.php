<?php

namespace App\Infrastructure\Controller;

use App\Application\Service\GetAllGendersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class GenderController extends AbstractController
{
    private GetAllGendersService $getAllGendersService;

    public function __construct(GetAllGendersService $getAllGendersService)
    {
        $this->getAllGendersService = $getAllGendersService;
    }

    #[Route('/api/genders', name: 'get_genders', methods: ['GET'])]
    #[OA\Get(
        path: '/api/genders',
        summary: 'Get all genders',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Returns the list of genders',
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
    public function getGenders(): JsonResponse
    {
        $genders = $this->getAllGendersService->execute();

        $data = array_map(function($gender) {
            return [
                'id' => $gender->getId(),
                'name' => $gender->getName(),
            ];
        }, $genders);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
