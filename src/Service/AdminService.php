<?php

namespace App\Service;

use App\DTO\IdArrayReq;
use App\DTO\UserDTO\UserBlockRequest;
use App\DTO\UserDTO\UserChangeRoleReq;
use App\DTO\UserDTO\UserListResponse;
use App\Enum\PaginationLimit;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminService
{
    public function __construct(private UserRepository $userRepository,
                                private PaginatorInterface $paginator,
                                private TranslatorInterface $translator,
                                private SearchService $searchService,)
    {
    }

    public function getListOfUsers(int $page): UserListResponse
    {
        $query = $this->userRepository->findAllUsers();
        $users = $this->paginator->paginate($query, $page, PaginationLimit::DEFAULT->value);
        return new UserListResponse($users->getTotalItemCount(), $users->getItems());
    }

    public function changeUserLockedStatus(UserBlockRequest $dto): string
    {
        $this->userRepository->changeUserLockedStatus($dto->getIds(), $dto->isStatus());
        return $this->translator->trans('user_blocked_response', [], 'api_success');
    }

    public function deleteUsers(IdArrayReq $dto): string
    {
        $this->searchService->deleteUsersFromElasticsearch($dto->getIds());
        $this->userRepository->deleteUsers($dto->getIds());
        return $this->translator->trans('user_deleted_response', [], 'api_success');
    }

    public function changeRole(UserChangeRoleReq $dto): string
    {
        $this->userRepository->changeUserRole($dto->getIds(), $dto->getUserRole()->value);
        return $this->translator->trans('user_role_changed_response', [], 'api_success');
    }
}