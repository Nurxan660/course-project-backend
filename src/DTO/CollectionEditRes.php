<?php

namespace App\DTO;

class CollectionEditRes
{
    private int $id;
    private string $name;
    private string $description;
    private ?string $imageUrl;
    private string $category;
    private array $customFields = [];

    /**
     * @param int $id
     * @param string $name
     * @param string $description
     * @param string|null $imageUrl
     * @param string $category
     * @param array $customFields
     */
    public function __construct(int $id, string $name, string $description, ?string $imageUrl, string $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->category = $category;
    }

    public function addCustomField(CustomField $customField): void
    {
        $this->customFields[] = $customField;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields): void
    {
        $this->customFields = $customFields;
    }
}