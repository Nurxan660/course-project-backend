<?php

namespace App\Service\Mapper;

use App\DTO\ItemListWithCollectionRes;
use App\DTO\Pojo\Item;

class ItemMapper
{
    public function mapToItemListWithCollectionDto(array $collectionWithItems): ItemListWithCollectionRes
    {
        $items = [];
        $collection = null;
        foreach ($collectionWithItems as $entry)
            $collection = $this->processEntry($items, $entry, $collection);
        $collection->setItems(array_values($items));
        return $collection;
    }

    private function processEntry(array &$items, $entry, &$collection): ItemListWithCollectionRes
    {
        $collection = $this->initializeCollectionWithItemDto($collection, $entry);
        $this->processItem($items, $entry);
        $collection->addCustomFieldName($entry['fieldName']);
        return $collection;
    }


    private function initializeCollectionWithItemDto($collection, $entry)
    {
        if (!$collection) {
            return new ItemListWithCollectionRes($entry['collectionName'], $entry['description'],
                $entry['imageUrl'], $entry['categoryName']);
        }
        return $collection;
    }

    private function processItem(&$items, $e): void
    {
        if (!isset($items[$e['name']])) {
            $items[$e['name']] = new Item($e['name']);
        }
        $items[$e['name']]->addCustomField($e['value']);
    }

}