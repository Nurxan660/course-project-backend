<?php

namespace App\Controller;

use App\Service\CollectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/collections', name: 'collections_')]
class CollectionController extends AbstractController
{
    public function __construct(private CollectionService  $collectionService)
    {
    }

    #[Route('/category', name: 'category', methods: ['GET'])]
    public function getCollectionsCategory(): JsonResponse {
        $categories = $this->collectionService->getCollectionsCategory();
        return new JsonResponse($categories);
    }
}