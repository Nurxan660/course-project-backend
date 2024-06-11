<?php

namespace App\DTO\JiraDTO;

class HelpRequestDto
{
    private string $description;
    private string $priority;
    private ?string $collection = null;
    private string $link;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function getCollection(): ?string
    {
        return $this->collection;
    }

    public function setCollection(?string $collection): void
    {
        $this->collection = $collection;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): void
    {
        $this->link = $link;
    }
}