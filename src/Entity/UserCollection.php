<?php

namespace App\Entity;

use App\Repository\UserCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UserCollectionRepository::class)]
class UserCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(targetEntity: CollectionCategory::class)]
    private CollectionCategory $category;

    #[ORM\OneToMany(targetEntity: CustomField::class, mappedBy: 'collection', cascade: ['persist', 'remove'])]
    private Collection $customFields;

    public function __construct(string $name, string $description, ?string $imageUrl, CollectionCategory $category)
    {
        $this->name = $name;
        $this->description = $description;
        $this->imageUrl = $imageUrl;
        $this->category = $category;
        $this->customFields = new ArrayCollection();
    }

    public function addCustomField(CustomField $customField): void {
        $customField->setCollection($this);
        $this->customFields->add($customField);
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getCategory(): CollectionCategory
    {
        return $this->category;
    }

    public function setCategory(CollectionCategory $category): void
    {
        $this->category = $category;
    }

    public function getCustomFields(): Collection
    {
        return $this->customFields;
    }

    public function setCustomFields(Collection $customFields): void
    {
        $this->customFields = $customFields;
    }
}
