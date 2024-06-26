<?php

namespace App\Service;

use App\DTO\CollectionDTO\CollectionDataReq;
use App\DTO\CollectionDTO\CollectionEditRes;
use App\DTO\CollectionDTO\CollectionPaginationRes;
use App\DTO\CollectionDTO\CollectionRes;
use App\DTO\IdArrayReq;
use App\Entity\CollectionCategory;
use App\Entity\User;
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
use Elastica\Index;
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
                                private CollectionMapper $collectionMapper,
                                private SearchService $searchService,
                                private Index $index)
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
            $req->getImageUrl(), $category, $this->security->getUser(), $req->isPublic());
    }

    public function getCollections(int $page): CollectionPaginationRes
    {
        $user = $this->security->getUser();
        $query = $this->collectionRepository->getCollectionsByUser($user);
        $pagination = $this->paginator->paginate($query, $page, PaginationLimit::DEFAULT->value);
        $collections = array_map([$this->collectionMapper, 'mapToCollection'], $pagination->getItems());
        return $this->collectionMapper->mapToPaginationRes($collections, $pagination->getTotalItemCount());
    }

    public function getLargestCollections(): array
    {
        return $this->collectionRepository->getLargestCollections();
    }

    public function deleteCollection(IdArrayReq $dto): string
    {
        $user = $this->security->getUser();
        $this->deleteCollectionFromElastic($user, $dto);
        $this->collectionRepository->deleteByIds($dto->getIds(), $user);
        return $this->translator->trans('collection_delete_response', [], 'api_success');
    }

    public function deleteCollectionFromElastic(User $user, IdArrayReq $dto): void
    {
        $query = $this->searchService->buildBoolQueryForUserAndIds('collection.id',
            'collection.user.id', $dto->getIds(), $user);
        $this->index->deleteByQuery($query);
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
    public function getCollectionBasic(int $collectionId): CollectionRes
    {
        $collection = $this->collectionRepository->getCollectionBasic($collectionId);
        if(!$collection) throw new CollectionNotFoundException();
        return $this->collectionMapper->mapToCollectionBasic($collection);
    }

    /**
     * @throws CollectionNotFoundException
     */
    public function getCollectionById(int $collectionId): UserCollection {
        $collection = $this->collectionRepository->find($collectionId);
        if(!$collection) throw new CollectionNotFoundException();
        return $collection;
    }

    public function getCollectionByIdAndUserId(int $collectionId, User $user): UserCollection {
        $collection = $this->collectionRepository->findByIdAndUserId($collectionId, $this->security->getUser());
        if(!$collection) throw new CollectionNotFoundException();
        return $collection;
    }

    /**
     * @throws CollectionNotFoundException
     * @throws CategoryNotFoundException
     */
    public function handleCollectionEdit(CollectionDataReq $req, int $collectionId): string
    {
        $collection = $this->getCollectionByIdAndUserId($collectionId, $this->security->getUser());
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
        $collection->setIsPublic($req->isPublic());
        return $collection;
    }
}