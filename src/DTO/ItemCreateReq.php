<?php

namespace App\DTO;
use App\Validator\Constraints\IsCustomFieldsRequired;
use Symfony\Component\Validator\Constraints as Assert;

#[IsCustomFieldsRequired]
class ItemCreateReq
{
    private int $collectionId;
    #[Assert\NotBlank]
    private string $name;
    #[Assert\NotBlank]
    private array $tags;
    private array $customFieldValues = [];

    public function getCollectionId(): int
    {
        return $this->collectionId;
    }

    public function setCollectionId(int $collectionId): void
    {
        $this->collectionId = $collectionId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function getCustomFieldValues(): array
    {
        return $this->customFieldValues;
    }

    public function setCustomFieldValues(array $customFieldValues): void
    {
        $this->customFieldValues = $customFieldValues;
    }
}