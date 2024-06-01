<?php

namespace App\DTO;

class IdArrayReq
{
    private array $ids;

    public function getIds(): array
    {
        return $this->ids;
    }

    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }
}