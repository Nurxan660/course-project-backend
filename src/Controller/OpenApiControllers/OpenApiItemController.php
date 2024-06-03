<?php

namespace App\Controller\OpenApiControllers;

use App\Exception\ItemNotFoundException;
use App\Service\ItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/open-api/item', name: 'open_api_item_')]
class OpenApiItemController extends AbstractController
{
    public function __construct(private ItemService $itemService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/get/last-added-items', name: 'get', methods: ['GET'])]
    public function getLargestCollections(): JsonResponse {
        $items = $this->itemService->getLastAddedItems();
        $jsonRes = $this->serializer->serialize($items, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }

    #[Route('/get/items', name: 'get_items', methods: ['GET'])]
    public function getCollectionWithItems(Request $request): JsonResponse {
        $collectionId = $request->query->getInt("collectionId");
        $page = $request->query->getInt("page");
        $res = $this->itemService->getCollectionWithItems($collectionId, $page);
        $jsonRes = $this->serializer->serialize($res, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }

    #[Route('/search/items', name: 'search_items', methods: ['GET'])]
    public function searchItems(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('term', '');
        $res = $this->itemService->searchItems($searchTerm);
        $resJson = $this->serializer->serialize($res, 'json');
        return new JsonResponse($resJson, Response::HTTP_OK, [], true);
    }
}