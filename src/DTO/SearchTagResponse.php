<?php

namespace App\DTO;

class SearchTagResponse
{
    private string $value;
    private string $label;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->label = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}