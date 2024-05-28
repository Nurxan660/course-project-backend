<?php

namespace App\Service\Mapper;

use App\DTO\ItemListRes;
use App\DTO\Pojo\Item;

class ItemMapper
{
    public function mapToItemListWithCollectionDto(array $collectionWithItems): ItemListRes
    {
        $items = [];
        $resDto = new ItemListRes();
        foreach ($collectionWithItems as $e) $resDto = $this->processEntry($items, $e, $resDto);
        $resDto->setItems(array_values($items));
        return $resDto;
    }

    private function processEntry(array &$items, $e, ItemListRes &$resDto): ItemListRes
    {
        $this->processItem($items, $e);
        $resDto->addCustomFieldName($e['fieldName'], $e['show']);
        return $resDto;
    }

    private function processItem(&$items, $e): void
    {
        if (!isset($items[$e['name']])) {
            $items[$e['name']] = new Item($e['name'], $e['id']);
        }
        if($e['show']) $items[$e['name']]->addCustomField($e['value']);
    }

}