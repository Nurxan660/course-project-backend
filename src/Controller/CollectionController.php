<?php

namespace App\Controller;

use App\DTO\CollectionCreateReq;
use App\Exception\CategoryNotFoundException;
use App\Exception\ValidationException;
use App\Service\CollectionService;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/collections', name: 'collections_')]
class CollectionController extends AbstractController
{
    public function __construct(private CollectionService  $collectionService,
                                private SerializerInterface $serializer,
                                private ValidatorService $validatorService)
    {
    }

    #[Route('/category', name: 'category', methods: ['GET'])]
    public function getCollectionsCategory(): JsonResponse {
        $categories = $this->collectionService->getCollectionsCategory();
        return new JsonResponse($categories);
    }

    /**
     * @throws CategoryNotFoundException
     * @throws ValidationException
     */
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createCollection(Request $request): JsonResponse {
        $collection = $this->serializer->deserialize($request->getContent(), CollectionCreateReq::class, 'json');
        $this->validatorService->validate($collection);
        $res = $this->collectionService->handleCollectionCreate($collection);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }
}