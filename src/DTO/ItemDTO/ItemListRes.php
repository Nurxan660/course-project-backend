<?php

namespace App\DTO\ItemDTO;

class ItemListRes
{
    private array $customFieldNames = [];
    private array $items = [];
    private int $totalPages;

    public function addCustomFieldName(?string $name, ?bool $show): void
    {
        if ($show && $name && !in_array($name, $this->customFieldNames)) {
            $this->customFieldNames[] = $name;
        }
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    public function getCustomFieldNames(): array
    {
        return $this->customFieldNames;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function setTotalPages(int $totalPages): void
    {
        $this->totalPages = $totalPages;
    }
}