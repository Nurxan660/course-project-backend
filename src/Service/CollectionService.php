<?php

namespace App\Service;

use App\Repository\CollectionCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CollectionService
{
    public function __construct(private CollectionCategoryRepository $categoryRepository)
    {
    }

    public function getCollectionsCategory(): array
    {
        return $this->categoryRepository->findAll();
    }
}