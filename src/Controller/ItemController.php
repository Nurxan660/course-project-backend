<?php

namespace App\Controller;

use App\DTO\ItemCreateReq;
use App\Exception\ValidationException;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/item', name: 'item_')]
class ItemController extends AbstractController
{
    public function __construct(private ValidatorService $validatorService)
    {
    }

    /**
     * @throws ValidationException
     */
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createItem(Request $request): JsonResponse {

        $req = $request->getContent();
        $collectionId = $request->query->getInt("collectionId");
        $data = json_decode($req, true);
        $dto = new ItemCreateReq($collectionId, $data);
        $this->validatorService->validate($dto);

        return new JsonResponse(["message" => 'sdv'], Response::HTTP_OK);
    }
}