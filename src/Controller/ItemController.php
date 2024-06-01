<?php

namespace App\Controller;

use App\DTO\DeleteItemReq;
use App\DTO\IdArrayReq;
use App\DTO\ItemDTO\ItemCreateReq;
use App\DTO\ItemDTO\ItemEditReq;
use App\Exception\CollectionNotFoundException;
use App\Exception\ValidationException;
use App\Service\ItemService;
use App\Service\TagService;
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
                                private ItemService  $itemService,
                                private TagService $tagService,)
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

    #[Route('/search/tags', name: 'tags', methods: ['GET'])]
    public function searchTags(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('term', '');
        $res = $this->tagService->searchTags($searchTerm);
        $resJson = $this->serializer->serialize($res, 'json');
        return new JsonResponse($resJson, Response::HTTP_OK, [], true);
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function deleteItemsByIds(Request $request): JsonResponse
    {
        $ids = $this->serializer->deserialize($request->getContent(), IdArrayReq::class, 'json');
        $res = $this->itemService->deleteItems($ids);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }

    #[Route('/edit', name: 'edit', methods: ['PUT'])]
    public function editItem(Request $request): JsonResponse
    {
        $itemId = $request->query->getInt("itemId");
        $dto = $this->serializer->deserialize($request->getContent(), ItemEditReq::class, 'json');
        $res = $this->itemService->handleItemEdit($itemId, $dto);
        return new JsonResponse(["message" => $res], Response::HTTP_OK);
    }
}