<?php

namespace App\DTO;

class CustomField
{
    public ?string $name;
    public ?string $type;
    public ?bool $isRequired;

    public function __construct(?string $name, ?string $type, ?bool $isRequired)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isRequired = $isRequired;
    }
}