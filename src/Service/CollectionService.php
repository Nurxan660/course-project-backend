<?php

namespace App\Service;

use App\DTO\CollectionDataReq;
use App\DTO\CollectionEditRes;
use App\DTO\CollectionPaginationRes;
use App\DTO\CollectionRes;
use App\Entity\CollectionCategory;
use App\Entity\UserCollection;
use App\Enum\PaginationLimit;
use App\Exception\CategoryNotFoundException;
use App\Exception\CollectionNotFoundException;
use App\Repository\UserCollectionRepository;
use App\Service\Mapper\CollectionMapper;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class CollectionService
{
    public function __construct(private CategoryService $categoryService,
                                private EntityManagerInterface $entityManager,
                                private CustomFieldService $customFieldService,
                                private TranslatorInterface $translator,
                                private Security $security,
                                private UserCollectionRepository $collectionRepository,
                                private PaginatorInterface $paginator,
                                private CollectionMapper $collectionMapper)
    {
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function handleCollectionCreate(CollectionDataReq $req): string
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

    private function createCollection(CollectionDataReq $req, CollectionCategory $category): UserCollection
    {
        return new UserCollection($req->getName(), $req->getDescription(),
            $req->getImageUrl(), $category, $this->security->getUser());
    }

    public function getCollections(int $page): CollectionPaginationRes
    {
        $user = $this->security->getUser();
        $query = $this->collectionRepository->getCollectionsByUser($user);
        $pagination = $this->paginator->paginate($query, $page, PaginationLimit::COLLECTION->value);
        $collections = array_map([$this->collectionMapper, 'mapToCollection'], $pagination->getItems());
        return $this->collectionMapper->mapToPaginationRes($collections, $pagination->getTotalItemCount());
    }

    public function deleteCollection(int $collectionId): string
    {
        $collection = $this->collectionRepository->find($collectionId);
        $this->entityManager->remove($collection);
        $this->entityManager->flush();
        return $this->translator->trans('collection_delete_response', [], 'api_success');
    }

    /**
     * @throws CollectionNotFoundException
     */
    public function getCollection(int $collectionId): CollectionEditRes
    {
        $query = $this->collectionRepository->getCollection($collectionId);
        if(!$query) throw new CollectionNotFoundException();
        $collectionDto = $this->collectionMapper->mapToEditCollectionDto($query);
        return $this->collectionMapper->mapToCollectionCustomField($query, $collectionDto);
    }

    /**
     * @throws CollectionNotFoundException
     */
    public function getCollectionById(int $collectionId): UserCollection {
        $collection = $this->collectionRepository->find($collectionId);
        if(!$collection) throw new CollectionNotFoundException();
        return $collection;
    }

    /**
     * @throws CollectionNotFoundException
     * @throws CategoryNotFoundException
     */
    public function handleCollectionEdit(CollectionDataReq $req, int $collectionId): string
    {
        $collection = $this->getCollectionById($collectionId);
        $category = $this->categoryService->getUpdatedCategory($req->getCategory(), $collection->getCategory());
        $updatedCollection = $this->changeCollection($collection, $req, $category);
        $this->customFieldService->updateCustomFields($updatedCollection, $req->getCustomFields());
        $this->saveCollection($updatedCollection);
        return $this->translator->trans('collection_edit_response', [], 'api_success');
    }

    private function changeCollection(UserCollection $collection, CollectionDataReq $req, CollectionCategory $category): UserCollection
    {
        $collection->setName($req->getName());
        $collection->setCategory($category);
        $collection->setDescription($req->getDescription());
        $collection->setImageUrl($req->getImageUrl());
        return $collection;
    }
}