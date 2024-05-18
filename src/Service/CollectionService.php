<?php

namespace App\Service;

use App\DTO\CollectionCreateReq;
use App\Entity\CollectionCategory;
use App\Entity\UserCollection;
use App\Exception\CategoryNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CollectionService
{
    public function __construct(private CategoryService $categoryService,
                                private EntityManagerInterface $entityManager,
                                private CustomFieldService $customFieldService,
                                private TranslatorInterface $translator)
    {
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function handleCollectionCreate(CollectionCreateReq $req): string
    {
        $category = $this->categoryService->getCategory($req->getCategory());
        $collection = $this->createCollection($req, $category);
        $this->customFieldService->addCustomFields($collection, $req->getCustomFields());
        $this->saveCollection($collection);
        return $this->translator->trans('collection_create_response', [], 'api_success');
    }

    private function saveCollection(UserCollection $collection): void
    {
        $this->entityManager->persist($collection);
        $this->entityManager->flush();
    }

    private function createCollection(CollectionCreateReq $req, CollectionCategory $category): UserCollection
    {
        return new UserCollection($req->getName(), $req->getDescription(),
            $req->getImageUrl(), $category);
    }
}