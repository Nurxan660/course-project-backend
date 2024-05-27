<?php

namespace App\Entity;

use App\Repository\ItemCustomFieldRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemCustomFieldRepository::class)]
class ItemCustomField
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Item::class, inversedBy: "itemCustomFields")]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private Item $item;

    #[ORM\ManyToOne(targetEntity: CustomField::class)]
    private CustomField $customField;

    #[ORM\Column(type: Types::TEXT)]
    private string $value;

    public function __construct(CustomField $customField, string $value)
    {
        $this->customField = $customField;
        $this->value = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): void
    {
        $this->item = $item;
    }

    public function getCustomField(): CustomField
    {
        return $this->customField;
    }

    public function setCustomField(CustomField $customField): void
    {
        $this->customField = $customField;
    }
}
