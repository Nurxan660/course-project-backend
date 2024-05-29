<?php

namespace App\Controller;

use App\Exception\ItemNotFoundException;
use App\Exception\UserNotFoundException;
use App\Service\LikeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/likes', name: 'likes_')]
class LikeController extends AbstractController
{
    public function __construct(private LikeService $likeService)
    {
    }

    /**
     * @throws UserNotFoundException
     * @throws ItemNotFoundException
     */
    #[Route('/toggle', name: 'toggle', methods: ['POST'])]
    public function toggleLike(Request $request): JsonResponse {
        $itemId = $request->get('itemId');
        $res = $this->likeService->toggleLike($itemId);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }
}