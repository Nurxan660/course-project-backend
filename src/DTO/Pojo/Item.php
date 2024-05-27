<?php

namespace App\DTO\Pojo;

class Item
{
    private string $itemName;
    private int $itemId;
    private array $customFieldValues = [];

    public function __construct(string $itemName, int $itemId)
    {
        $this->itemName = $itemName;
        $this->itemId = $itemId;
    }

    public function addCustomField($value): void
    {
        $this->customFieldValues[] = $value;
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getCustomFieldValues(): array
    {
        return $this->customFieldValues;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }
}