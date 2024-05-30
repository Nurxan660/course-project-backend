<?php

namespace App\DTO\CollectionDTO;
use Symfony\Component\Validator\Constraints as Assert;

class CollectionDataReq
{
    #[Assert\NotBlank]
    private string $name;
    #[Assert\NotBlank]
    private string $description;
    #[Assert\NotBlank]
    private string $category;
    private ?string $imageUrl = null;
    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'name' => new Assert\NotBlank(),
                'type' => new Assert\NotBlank(),
                'isRequired' => new Assert\Type('bool'),
                'showInTable' => new Assert\Type('bool')
            ],
            'allowExtraFields' => false
        ])
    ])]
    private array $customFields = [];

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

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
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