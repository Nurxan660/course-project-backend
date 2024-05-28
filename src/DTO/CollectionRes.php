<?php

namespace App\DTO;

use App\Entity\CollectionCategory;

class CollectionRes
{
    private int $id;
    private string $name;
    private string $description;
    private ?string $imageUrl;
    private string $category;

     function __construct(int $id, string $name, string $category, string $description = '', string $imageUrl = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function getCategory(): string
    {
        return $this->category;
    }
}