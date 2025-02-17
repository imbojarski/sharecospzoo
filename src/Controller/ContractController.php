<?php

namespace App\Controller;

use App\Entity\RentalContract;
use App\Repository\RentalContractRepository;
use App\Service\RentService\RentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
#[OA\Tag(name: 'Rental Contract')]
final class ContractController extends AbstractController
{
    private const SUCCESSFUL_DESCRIPTION = 'Successful operation';
    private const BAD_REQUEST_DESCRIPTION = 'Bad request';
    private const INTERNAL_ERROR_DESCRIPTION = 'An error occurred';

    #[OA\Get(
        path: "/contract",
        summary: "Retrieve contract information",
    )]

    #[OA\Response(response: Response::HTTP_OK, description: self::SUCCESSFUL_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: self::BAD_REQUEST_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: self::INTERNAL_ERROR_DESCRIPTION)]
    #[OA\Parameter(
        name: 'limit',
        description: 'Limits the number of returned contracts',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'integer'),
        example: 10,
    )]
    #[OA\Parameter(
        name: 'order',
        description: 'Order returned contracts',
        in: 'query',
        required: false,
        schema: new OA\Schema(type: 'string', enum: [
            'ASC',
            'DESC'
        ]),
        example: 'ASC'
    )]

    #[Route('/contract', name: 'app_contract', methods: ['GET'])]
    public function index(
        RentalContractRepository $rentalContractRepository,
        SerializerInterface $serializer,
        Request $request
    ): JsonResponse {
        $limit = (int)$request->query->get('limit');
        $order = (string)$request->query->get('order');
        $contracts = $rentalContractRepository->findBy([], ['id' => $order], limit: $limit);
        $response = json_decode($serializer->serialize($contracts, 'json'));

        return new JsonResponse($response);
    }
}