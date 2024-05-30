<?php

namespace App\DTO\ItemDTO;

class ItemWithLikesResponse
{
    private string $name;
    private int $likesCount;
    private array $customFields;

    /**
     * @param string $name
     * @param int $likesCount
     */
    public function __construct(string $name, int $likesCount)
    {
        $this->name = $name;
        $this->likesCount = $likesCount;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLikesCount(): int
    {
        return $this->likesCount;
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