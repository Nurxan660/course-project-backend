<?php

namespace App\Service;

use App\Entity\CollectionCategory;
use App\Exception\CategoryNotFoundException;
use App\Repository\CollectionCategoryRepository;

class CategoryService
{
    public function __construct(private CollectionCategoryRepository $categoryRepository)
    {
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepository->findAll();
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function getCategory(string $category): CollectionCategory {
        $category = $this->categoryRepository->findOneBy(['name' => $category]);
        if (!$category) throw new CategoryNotFoundException();
        return $category;
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function getUpdatedCategory(string $currentCategory, CollectionCategory $collectionCategory): CollectionCategory
    {
        if($currentCategory !== $collectionCategory->getName()) {
            return $this->getCategory($currentCategory);
        }
        return $collectionCategory;
    }
}