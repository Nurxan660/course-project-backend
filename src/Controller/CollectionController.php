<?php

namespace App\Controller;

use App\DTO\CollectionDataReq;
use App\Exception\CategoryNotFoundException;
use App\Exception\CollectionNotFoundException;
use App\Exception\ValidationException;
use App\Service\CategoryService;
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
                                private ValidatorService $validatorService,
                                private CategoryService $categoryService)
    {
    }

    #[Route('/category', name: 'category', methods: ['GET'])]
    public function getCollectionsCategory(): JsonResponse {
        $categories = $this->categoryService->getAllCategories();
        return new JsonResponse($categories);
    }

    /**
     * @throws CategoryNotFoundException
     * @throws ValidationException
     */
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createCollection(Request $request): JsonResponse {
        $collection = $this->serializer->deserialize($request->getContent(), CollectionDataReq::class, 'json');
        $this->validatorService->validate($collection);
        $res = $this->collectionService->handleCollectionCreate($collection);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }

    #[Route('/get', name: 'get', methods: ['GET'])]
    public function getCollections(Request $request): JsonResponse {
        $page = $request->query->getInt('page', 1);
        $res = $this->collectionService->getCollections($page);
        $jsonRes = $this->serializer->serialize($res, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }

    #[Route('/delete', name: 'delete', methods: ['DELETE'])]
    public function deleteCollection(Request $request): JsonResponse {
        $collectionId = $request->query->getInt('collectionId');
        $res = $this->collectionService->deleteCollection($collectionId);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }

    /**
     * @throws CollectionNotFoundException
     */
    #[Route('/get/collection', name: 'get_collection', methods: ['GET'])]
    public function getCollection(Request $request): JsonResponse {
        $collectionId = $request->query->getInt('collectionId');
        $res = $this->collectionService->getCollection($collectionId);
        $jsonRes = $this->serializer->serialize($res, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }

    /**
     * @throws CollectionNotFoundException
     */
    #[Route('/get/collection/basic', name: 'get_collection_basic', methods: ['GET'])]
    public function getCollectionBasic(Request $request): JsonResponse {
        $collectionId = $request->query->getInt('collectionId');
        $res = $this->collectionService->getCollectionBasic($collectionId);
        $jsonRes = $this->serializer->serialize($res, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }

    /**
     * @throws CollectionNotFoundException
     * @throws ValidationException
     * @throws CategoryNotFoundException
     */
    #[Route('/edit', name: 'edit', methods: ['PUT'])]
    public function editCollection(Request $request): JsonResponse {
        $collection = $this->serializer->deserialize($request->getContent(), CollectionDataReq::class, 'json');
        $collectionId = $request->query->getInt('collectionId');
        $this->validatorService->validate($collection);
        $res = $this->collectionService->handleCollectionEdit($collection, $collectionId);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }
}