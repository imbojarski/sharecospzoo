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
use Throwable;

#[AsController]
#[OA\Tag(name: 'Rental Contract')]
final class ContractController extends AbstractController
{
    private const SUCCESSFUL_DESCRIPTION = 'Successful operation';
    private const BAD_REQUEST_DESCRIPTION = 'Bad request';
    private const INTERNAL_ERROR_DESCRIPTION = 'An error occurred';
    private const NOT_FOUND_DESCRIPTION = 'Contract not found';

    #[OA\Get(
        path: "/contract",
        summary: "Retrieve contract information",
    )]
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
    #[OA\Response(response: Response::HTTP_OK, description: self::SUCCESSFUL_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: self::BAD_REQUEST_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: self::INTERNAL_ERROR_DESCRIPTION)]
    #[Route('/contract', name: 'app_contract_index', methods: ['GET'])]
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

    #[OA\Get(
        path: "/contract/{id}",
        summary: "Retrieve contract details",
    )]
    #[OA\Response(response: Response::HTTP_OK, description: self::SUCCESSFUL_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: self::NOT_FOUND_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_BAD_REQUEST, description: self::BAD_REQUEST_DESCRIPTION)]
    #[OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: self::INTERNAL_ERROR_DESCRIPTION)]
    #[Route('/contract/{id}', name: 'app_contract_details', methods: ['GET'])]
    public function getContractDetails(
        RentService $rentService,
        SerializerInterface $serializer,
        int $id
    ): JsonResponse {
        try {
            $contract = $rentService->getRentalContract($id);

            $response = json_decode($serializer->serialize($contract, 'json'));
        } catch (Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($response);
    }
}
