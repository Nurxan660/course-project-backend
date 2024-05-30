<?php

namespace App\Controller;

use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SearchController extends AbstractController
{
    public function __construct(private SearchService $searchService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function searchItems(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('term', '');
        $res = $this->searchService->searchItems($searchTerm);
        $resJson = $this->serializer->serialize($res, 'json');
        return new JsonResponse($resJson, Response::HTTP_OK, [], true);
    }
}