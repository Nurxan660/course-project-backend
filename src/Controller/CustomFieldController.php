<?php

namespace App\Controller;

use App\Service\CollectionService;
use App\Service\CustomFieldService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/customFields', name: 'customFields_')]
class CustomFieldController extends AbstractController
{
    public function __construct(private CustomFieldService $customFieldService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/get', name: 'get', methods: ['GET'])]
    public function getCollectionCustomFields(Request $request): JsonResponse {
        $collectionId = $request->query->getInt('collectionId');
        $customFields = $this->customFieldService->getCustomFields($collectionId);
        $jsonCustomFields = $this->serializer->serialize($customFields, 'json');
        return new JsonResponse($jsonCustomFields, Response::HTTP_OK, [], true);
    }

}