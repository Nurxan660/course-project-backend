<?php

namespace App\DTO\Pojo;

class Item
{
    private string $itemName;
    private array $customFieldValues = [];

    public function __construct(string $itemName)
    {
        $this->itemName = $itemName;
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
}