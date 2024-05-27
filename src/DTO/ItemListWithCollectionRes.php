<?php

namespace App\DTO;

class ItemListWithCollectionRes
{
    private string $name;
    private string $description;
    private string $imageUrl;
    private string $categoryName;
    private array $customFieldNames = [];
    private array $items = [];
    private int $totalPages;


    public function __construct(string $name, string $description, string $imageUrl, string $categoryName)
    {
        $this->name = $name;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->categoryName = $categoryName;
    }

    public function addCustomFieldName(string $name): void
    {
        if (!in_array($name, $this->customFieldNames)) {
            $this->customFieldNames[] = $name;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function getCategoryName(): string
    {
        return $this->categoryName;
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