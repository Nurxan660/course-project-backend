<?php

namespace App\DTO;
use App\Validator\Constraints\IsCustomFieldsRequired;
use Symfony\Component\Validator\Constraints as Assert;

#[IsCustomFieldsRequired]
class ItemCreateReq
{
    private int $collectionId;
    #[Assert\Collection([
        'fields' => [
            'name' => new Assert\NotBlank(),
            'tags' => new Assert\NotBlank(),
        ],
        'allowExtraFields' => true,
    ])]
    private array $customFieldValues;

    public function __construct(int $collectionId, array $customFieldValues)
    {
        $this->collectionId = $collectionId;
        $this->customFieldValues = $customFieldValues;
    }

    public function getCollectionId(): int
    {
        return $this->collectionId;
    }

    public function getCustomFieldValues(): array
    {
        return $this->customFieldValues;
    }
}