<?php

namespace App\DTO;

class CustomField
{
    public int $id;
    public string $name;
    public string $type;

    public function __construct($id, $name, $type) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }
}