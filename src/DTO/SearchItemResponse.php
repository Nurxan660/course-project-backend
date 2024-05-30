<?php

namespace App\DTO;

class SearchItemResponse
{
    private ?int $id;
    private string $name;
    private ?int $collectionId;

    public function __construct(?int $id, string $name, ?int $collectionId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->collectionId = $collectionId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCollectionId(): ?int
    {
        return $this->collectionId;
    }
}