<?php

namespace App\Service\Mapper;

use App\DTO\ItemListRes;
use App\DTO\ItemWithLikesResponse;
use App\DTO\Pojo\CustomFieldItemWithLikes;
use App\DTO\Pojo\Item;
use App\Entity\ItemCustomField;

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

    public function mapToItemWithLikesDto(\App\Entity\Item $item): ItemWithLikesResponse
    {
        $customFields = [];
        $dto = new ItemWithLikesResponse($item->getName(), $item->getLikes()->count());
        $this->getItemWithLikesDtoCustomFields($customFields, $item);
        $dto->setCustomFields($customFields);
        return $dto;
    }

    private function getItemWithLikesDtoCustomFields(array &$customFields, \App\Entity\Item $item): void
    {
        foreach ($item->getItemCustomFields() as $icf) {
            if(!$icf instanceof ItemCustomField) break;
            $customFields[] = new CustomFieldItemWithLikes($icf->getCustomField()->getName(),
                $icf->getValue(), $icf->getCustomField()->getType());
        }
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