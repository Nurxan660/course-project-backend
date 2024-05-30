<?php

namespace App\Controller;

use App\DTO\UserDTO\UserBlockRequest;
use App\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    public function __construct(private AdminService $adminService,
                                private SerializerInterface $serializer)
    {
    }

    #[Route('/get/users', name: 'get_users', methods: ['GET'])]
    public function getListOfUsers(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $dtoRes = $this->adminService->getListOfUsers($page);
        $jsonRes = $this->serializer->serialize($dtoRes, 'json');
        return new JsonResponse($jsonRes, Response::HTTP_OK, [], true);
    }

    #[Route('/block/users', name: 'block_users', methods: ['PUT'])]
    public function changeUserLockedStatus(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), UserBlockRequest::class, 'json');
        $message = $this->adminService->changeUserLockedStatus($dto);
        return new JsonResponse(['message' => $message]);
    }
}