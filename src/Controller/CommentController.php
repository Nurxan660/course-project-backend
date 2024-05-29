<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    public function addComment(Request $request, HubInterface $hub): JsonResponse
    {
        $itemId = $request->query->getInt('itemId');

        $update = new Update(
            "comments/{$itemId}",
            json_encode([
                'author' => $this->getUser()->getUserIdentifier()
            ])
        );
        $hub->publish($update);

        return new JsonResponse(["message" => 'Added'], Response::HTTP_OK);

    }
}