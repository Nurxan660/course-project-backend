<?php

namespace App\DTO\ItemDTO;
use App\Validator\Constraints\IsCustomFieldsRequired;
use Symfony\Component\Validator\Constraints as Assert;

#[IsCustomFieldsRequired]
class ItemEditReq
{
    private array $customFields;
    #[Assert\NotBlank]
    private array $tags;
    private string $name;

    public function getCustomFields(): array
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}