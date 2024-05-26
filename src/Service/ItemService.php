<?php

namespace App\Service;

use App\DTO\ItemCreateReq;
use App\Entity\Item;
use App\Entity\ItemCustomField;
use App\Entity\Tag;
use App\Entity\UserCollection;
use App\Exception\CollectionNotFoundException;
use App\Repository\CustomFieldRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ItemService
{
    public function __construct(private CollectionService $collectionService,
                                private CustomFieldRepository $customFieldRepository,
                                private CustomFieldService $customFieldService,
                                private TranslatorInterface $translator,
                                private EntityManagerInterface $entityManager,
                                private TagService $tagService)
    {
    }

    /**
     * @throws CollectionNotFoundException
     */
    public function handleItemCreate(ItemCreateReq $dto): string
    {
        $collection = $this->collectionService->getCollectionById($dto->getCollectionId());
        $item = $this->initializeItem($dto, $collection);
        $this->saveCollection($item);
        return $this->translator->trans('item_create_response', [], 'api_success');
    }

    private function initializeItem(ItemCreateReq $dto, UserCollection $collection): Item
    {
        $item = $this->createItem($dto, $collection);
        $this->addItemTags($dto, $item);
        $customFieldsMap = $this->customFieldService->getCustomFieldsMap(array_keys($dto->getCustomFieldValues()));
        $this->addItemCustomFields($dto, $item, $customFieldsMap);
        return $item;
    }

    private function saveCollection(Item $item): void
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    private function addItemCustomFields(ItemCreateReq $dto, Item $item, array $customFieldsMap): void {
        foreach ($dto->getCustomFieldValues() as $name => $customFieldValue) {
            if(!isset($customFieldsMap[$name])) continue;
                $itemCustomField = new ItemCustomField($customFieldsMap[$name], $customFieldValue);
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
}