<?php

namespace App\Service;

use App\DTO\CollectionCreateReq;
use App\DTO\CollectionPaginationRes;
use App\DTO\CollectionRes;
use App\Entity\CollectionCategory;
use App\Entity\UserCollection;
use App\Enum\PaginationLimit;
use App\Exception\CategoryNotFoundException;
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
            $req->getImageUrl(), $category, $this->security->getUser());
    }

    public function getCollection(int $page): CollectionPaginationRes
    {
        $user = $this->security->getUser();
        $query = $this->collectionRepository->getCollectionsByUser($user);
        $pagination = $this->paginator->paginate($query, $page, PaginationLimit::COLLECTION->value);
        $collections = array_map([$this->collectionMapper, 'mapToCollection'], $pagination->getItems());
        return $this->collectionMapper->mapToPaginationRes($collections, $pagination->getTotalItemCount());
    }
}