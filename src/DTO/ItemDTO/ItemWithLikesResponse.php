<?php

namespace App\DTO\ItemDTO;

class ItemWithLikesResponse
{
    private string $name;
    private int $likesCount;
    private array $customFields;
    private bool $isLiked;

    /**
     * @param string $name
     * @param int $likesCount
     */
    public function __construct(string $name, int $likesCount, $isLiked)
    {
        $this->name = $name;
        $this->likesCount = $likesCount;
        $this->isLiked = $isLiked;
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

    public function isLiked(): bool
    {
        return $this->isLiked;
    }
}