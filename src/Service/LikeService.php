<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\Like;
use App\Entity\User;
use App\Exception\ItemNotFoundException;
use App\Exception\UserNotFoundException;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class LikeService
{

    public function __construct(private EntityManagerInterface $entityManager,
                                private AuthService  $authService,
                                private ItemService  $itemService,
                                private LikeRepository $likeRepository,
                                private TranslatorInterface $translator,
                                private Security $security)
    {
    }

    /**
     * @throws ItemNotFoundException
     * @throws UserNotFoundException
     */
    public function toggleLike(int $itemId): string
    {
        $user = $this->security->getUser();
        $item = $this->itemService->findById($itemId);
        $like = $this->likeRepository->findOneBy(['user' => $user, 'item' => $item]);
        $like ? $res = $this->removeLike($like) : $res = $this->saveLike($user, $item);
        return $res;
    }

    public function saveLike(User $user, Item $item): string {
        $like = new Like($user, $item);
        $this->entityManager->persist($like);
        $this->entityManager->flush();
        return $this->translator->trans('like_created', [], 'api_success');
    }

    public function removeLike(Like $like): string {
        $this->entityManager->remove($like);
        $this->entityManager->flush();
        return $this->translator->trans('like_removed', [], 'api_success');
    }
}