<?php

namespace App\Controller;

use App\Service\ItemService;
use App\Service\SearchService;
use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/open-api/search', name: 'search')]
class SearchController extends AbstractController
{
    public function __construct(private ItemService $itemService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/items', name: 'items', methods: ['GET'])]
    public function searchItems(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('term', '');
        $res = $this->itemService->searchItems($searchTerm);
        $resJson = $this->serializer->serialize($res, 'json');
        return new JsonResponse($resJson, Response::HTTP_OK, [], true);
    }
}