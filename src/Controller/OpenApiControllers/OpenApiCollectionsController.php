<?php

namespace App\Controller\OpenApiControllers;

use App\Exception\CollectionNotFoundException;
use App\Service\CollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/open-api/collections', name: 'open_api_collections_')]
class OpenApiCollectionsController extends AbstractController
{
    public function __construct(private CollectionService $collectionService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/get', name: 'get', methods: ['GET'])]
    public function getLargestCollections(): JsonResponse {
        $collections = $this->collectionService->getLargestCollections();
        $jsonRes = $this->serializer->serialize($collections, 'json');
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
}