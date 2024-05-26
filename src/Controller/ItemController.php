<?php

namespace App\Controller;

use App\DTO\ItemCreateReq;
use App\Exception\CollectionNotFoundException;
use App\Exception\ValidationException;
use App\Service\ItemService;
use App\Service\Mapper\ItemMapper;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/item', name: 'item_')]
class ItemController extends AbstractController
{
    public function __construct(private ValidatorService $validatorService,
                                private SerializerInterface $serializer,
                                private ItemService  $itemService,)
    {
    }

    /**
     * @throws ValidationException
     * @throws CollectionNotFoundException
     */
    #[Route('/create', name: 'create', methods: ['POST'])]
    public function createItem(Request $request): JsonResponse {
        $itemDto = $this->serializer->deserialize($request->getContent(), ItemCreateReq::class, 'json');
        $this->validatorService->validate($itemDto);
        $res = $this->itemService->handleItemCreate($itemDto);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }
}