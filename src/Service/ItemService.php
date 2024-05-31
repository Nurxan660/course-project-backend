<?php

namespace App\Service;

use App\DTO\DeleteItemReq;
use App\DTO\ItemDTO\ItemCreateReq;
use App\DTO\ItemDTO\ItemEditReq;
use App\DTO\ItemDTO\ItemListRes;
use App\DTO\ItemDTO\ItemWithLikesResponse;
use App\Entity\Item;
use App\Entity\ItemCustomField;
use App\Entity\Tag;
use App\Entity\UserCollection;
use App\Exception\CollectionNotFoundException;
use App\Exception\ItemNotFoundException;
use App\Repository\CustomFieldRepository;
use App\Repository\ItemRepository;
use App\Repository\UserCollectionRepository;
use App\Service\Mapper\ItemMapper;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ItemService
{
    public function __construct(private CollectionService $collectionService,
                                private CustomFieldRepository $customFieldRepository,
                                private CustomFieldService $customFieldService,
                                private TranslatorInterface $translator,
                                private EntityManagerInterface $entityManager,
                                private TagService $tagService,
                                private ItemMapper $itemMapper,
                                private UserCollectionRepository $collectionRepository,
                                private ItemRepository $itemRepository,
                                private PaginatorInterface $paginator,
                                private TransformedFinder $finder,
                                private SearchService  $searchService)
    {
    }

    /**
     * @throws CollectionNotFoundException
     */
    public function handleItemCreate(ItemCreateReq $dto): string
    {
        $collection = $this->collectionService->getCollectionById($dto->getCollectionId());
        $item = $this->initializeItem($dto, $collection);
        $this->saveItem($item);
        return $this->translator->trans('item_create_response', [], 'api_success');
    }

    public function searchItems(string $searchTerm): array {
        $query = $this->searchService->getSearchQuery($searchTerm);
        $results = $this->finder->findRaw($query);
        return $this->itemMapper->mapToSearchItemResponseDto($results);
    }

    private function initializeItem(ItemCreateReq $dto, UserCollection $collection): Item
    {
        $item = $this->createItem($dto, $collection);
        $this->addItemTags($dto, $item);
        $customFieldsMap = $this->customFieldService->getCustomFieldsMap(array_keys($dto->getCustomFieldValues()));
        $this->addItemCustomFields($dto, $item, $customFieldsMap);
        return $item;
    }

    private function saveItem(Item $item): void
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    private function addItemCustomFields(ItemCreateReq $dto, Item $item, array $customFieldsMap): void {
        foreach ($dto->getCustomFieldValues() as $name => $customFieldValue) {
            if (!isset($customFieldsMap[$name])) continue;
            $value = trim($customFieldValue) === '' ? null : $customFieldValue;
            $itemCustomField = new ItemCustomField($customFieldsMap[$name], $value);
            $item->addItemCustomField($itemCustomField);
        }
    }

    private function addItemTags(ItemCreateReq $dto, Item $item): void {
        $tagsMap = $this->tagService->getTagsMap($dto->getTags());
        foreach ($dto->getTags() as $tagName) {
            if(!isset($tagsMap[$tagName])) $this->addItemNotExistingTags($tagName, $item);
            else $item->addTag($tagsMap[$tagName]);
        }
    }

    private function addItemNotExistingTags(string $tagName, Item $item): void
    {
        $tag = new Tag($tagName);
        $item->addTag($tag);
    }

    private function createItem(ItemCreateReq $dto, UserCollection $collection): Item
    {
        return new Item($dto->getName(), $collection);
    }

    public function getCollectionWithItems(int $collectionId, int $page): ItemListRes
    {
        $query = $this->itemRepository->findItemsWithCustomFields($collectionId);
        $items = $this->paginator->paginate($query, $page, 10);
        $resDto = $this->itemMapper->mapToItemListWithCollectionDto($items->getItems());
        $resDto->setTotalPages($items->getTotalItemCount());
        return $resDto;
    }

    public function deleteItems(DeleteItemReq $req): string
    {
        $this->itemRepository->deleteByIds($req->getIds());
        return $this->translator->trans('collection_delete_response', [], 'api_success');
    }

    public function handleItemEdit(int $itemId, ItemEditReq $data): string
    {
        $item = $this->itemRepository->findItemById($itemId);
        $this->customFieldService->updateCustomFieldValues($item, $data->getCustomFields());
        $item->setName($data->getName());
        $this->tagService->updateTags($item, $data->getTags());
        return $this->translator->trans('item_edit_response', [], 'api_success');
    }

    /**
     * @throws ItemNotFoundException
     */
    public function findById(int $itemId): Item
    {
        $item = $this->itemRepository->find($itemId);
        if(!$item) throw new ItemNotFoundException();
        return $item;
    }

    /**
     * @throws ItemNotFoundException
     */
    public function getItemWithLikes(int $itemId): ItemWithLikesResponse
    {
        $res = $this->itemRepository->getItemWithLikes($itemId);
        if(!$res) throw new ItemNotFoundException();
        return $this->itemMapper->mapToItemWithLikesDto($res);
    }

    public function getLastAddedItems(): array
    {
        return $this->itemRepository->getLastAddedItems();
    }
}