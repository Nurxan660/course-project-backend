<?php

namespace App\DTO;

class DeleteItemReq
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