<?php

namespace App\Controller\OpenApiControllers;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/open-api/tag', name: 'open_api_tag_')]
class OpenApiTagController extends AbstractController
{
    public function __construct(private TagService $tagService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/get/popular', name: 'get_popular', methods: ['GET'])]
    public function getPopularTags(): JsonResponse {
        $tags= $this->tagService->getPopularTags();
        $jsonRes = $this->serializer->serialize($tags, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }
}