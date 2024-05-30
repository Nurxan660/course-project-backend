<?php

namespace App\Entity;

use App\Repository\CustomFieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: CustomFieldRepository::class)]
#[ORM\UniqueConstraint(name: "unique_name_collection", columns: ["name", "collection_id"])]
#[ORM\Index(name: 'field_name_idx', columns: ['name'])]
#[ORM\Index(name: 'collection_idx', columns: ['collection_id'])]
class CustomField
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $type;

    #[ORM\Column]
    private bool $isRequired = false;

    #[ORM\Column]
    private bool $showInTable = false;

    #[ORM\ManyToOne(targetEntity: UserCollection::class, inversedBy: "customFields")]
    #[Ignore]
    private UserCollection $collection;

    public function __construct(string $name, string $type, bool $isRequired, bool $showInTable)
    {
        $this->name = $name;
        $this->type = $type;
        $this->isRequired = $isRequired;
        $this->showInTable = $showInTable;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCollection(): UserCollection
    {
        return $this->collection;
    }

    public function setCollection(UserCollection $collection): void
    {
        $this->collection = $collection;
    }

    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): void
    {
        $this->isRequired = $isRequired;
    }

    public function isShowInTable(): bool
    {
        return $this->showInTable;
    }

    public function setShowInTable(bool $showInTable): void
    {
        $this->showInTable = $showInTable;
    }
}
