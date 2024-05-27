<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\UniqueConstraint(name: "unique_item_name_collection", columns: ["name", "collection_id"])]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: UserCollection::class, inversedBy: "items")]
    private UserCollection $collection;

    #[ORM\OneToMany(targetEntity: ItemCustomField::class, mappedBy: 'item', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    private Collection $itemCustomFields;

    #[ORM\ManyToMany(targetEntity: "Tag", cascade: ['persist', 'remove'])]
    private Collection $tags;

    public function __construct(string $name, UserCollection $userCollection)
    {
        $this->name = $name;
        $this->collection = $userCollection;
        $this->itemCustomFields = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function addItemCustomField(ItemCustomField $itemCustomField): void {
        $itemCustomField->setItem($this);
        $this->itemCustomFields->add($itemCustomField);
    }

    public function addTag(Tag $tag): void {
        $this->tags->add($tag);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUserCollection(): UserCollection
    {
        return $this->collection;
    }

    public function setUserCollection(UserCollection $userCollection): void
    {
        $this->collection = $userCollection;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function getItemCustomFields(): Collection
    {
        return $this->itemCustomFields;
    }
}
