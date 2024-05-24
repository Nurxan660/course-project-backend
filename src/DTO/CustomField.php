<?php

namespace App\DTO;

class CustomField
{
    public ?string $name;
    public ?string $type;

    public function __construct($name, $type) {
        $this->name = $name;
        $this->type = $type;
    }
}