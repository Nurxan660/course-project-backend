<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
#[ORM\Index(name: 'user_item_idx', columns: ['user_id', 'item_id'])]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "likes")]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: "likes")]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Item $item;

    /**
     * @param User $user
     * @param User $item
     */
    public function __construct(User $user, Item $item)
    {
        $this->user = $user;
        $this->item = $item;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(User $item): void
    {
        $this->item = $item;
    }
}
