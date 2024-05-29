<?php

namespace App\DTO\Pojo;

class CustomFieldItemWithLikes
{
    private string $customFieldName;
    private string $value;
    private string $type;

    public function __construct(string $customFieldName, string $value, string $type)
    {
        $this->customFieldName = $customFieldName;
        $this->value = $value;
        $this->type = $type;
    }

    public function getCustomFieldName(): string
    {
        return $this->customFieldName;
    }


    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }
}