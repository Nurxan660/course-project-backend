<?php

namespace App\Service;

use App\Entity\CustomField;
use App\Entity\Item;
use App\Entity\ItemCustomField;
use App\Entity\UserCollection;
use App\Repository\CustomFieldRepository;
use App\Repository\ItemCustomFieldRepository;
use App\Repository\ItemRepository;
use App\Service\Mapper\CustomFieldMapper;
use Doctrine\ORM\EntityManagerInterface;

class CustomFieldService
{

    public function __construct(private EntityManagerInterface $entityManager,
                                private CustomFieldRepository $customFieldRepository,
                                private ItemRepository $itemRepository,
                                private CustomFieldMapper  $customFieldMapper)
    {
    }

    public function addCustomFields(UserCollection $collection, array $fieldsData): void {
        foreach ($fieldsData as $fd) {
            $customField = new CustomField($fd['name'], $fd['type'],
                $fd['isRequired'], $fd['showInTable']);
            $collection->addCustomField($customField);
        }
    }

    public function getCustomFields(int $collectionId): array {
        return $this->customFieldRepository->findBy(['collection' => $collectionId]);
    }

    public function getCustomFieldsMap(array $customFieldNames): array
    {
        $customFields = $this->customFieldRepository->findBy(['name' => $customFieldNames]);
        $customFieldsMap = [];
        foreach ($customFields as $customField)
            $customFieldsMap[$customField->getName()] = $customField;
        return $customFieldsMap;
    }

    public function updateCustomFields(UserCollection $currentFields, array $newFieldsData): void
    {
        $currentFieldsMap = $this->mapCurrentFields($currentFields);
        $newFieldsMap = $this->mapNewFieldsData($newFieldsData);
        $this->removeFields(array_diff_key($currentFieldsMap, $newFieldsMap));
        $this->addCustomFields($currentFields, array_diff_key($newFieldsMap, $currentFieldsMap));
        $this->entityManager->flush();
    }

    public function updateCustomFieldValues(Item $item, array $customFields): void
    {
        $existingFields = $this->indexExistingFields($item);
        $this->updateExistingFields($existingFields, $customFields);
        $this->addNewCustomFields($item, $existingFields, $customFields);
    }

    private function indexExistingFields(Item $item): array
    {
        $indexedFields = [];
        foreach ($item->getItemCustomFields() as $field) {
            if (!$field instanceof ItemCustomField || !$field->getCustomField()) continue;
            $indexedFields[$field->getCustomField()->getName()] = $field;
        }
        return $indexedFields;
    }

    private function updateExistingFields(array $existingFields, array $customFields): void
    {
        foreach ($existingFields as $fieldName => $field) {
            if (isset($customFields[$fieldName]) && $field->getValue() !== $customFields[$fieldName]) {
                $field->setValue($customFields[$fieldName]);
            }
        }
    }

    private function addNewCustomFields(Item $item, array $existingFields, array $customFields): void
    {
        foreach ($customFields as $fieldName => $value) {
            if (!isset($existingFields[$fieldName])) {
                $this->createAndAddNewField($item, $fieldName, $value);
            }
        }
    }

    private function createAndAddNewField(Item $item, string $fieldName, $value): void
    {
        $customField = $this->customFieldRepository->findOneBy(['name' => $fieldName]);
        $newField = new ItemCustomField($customField, $value);
        $newField->setItem($item);
        $this->entityManager->persist($newField);
        $item->addItemCustomField($newField);
    }

    private function mapCurrentFields(UserCollection $currentFields): array
    {
        $currentFieldsMap = [];
        foreach ($currentFields->getCustomFields() as $field) {
            $currentFieldsMap[$field->getName()] = $field;
        }
        return $currentFieldsMap;
    }

    private function mapNewFieldsData(array $newFieldsData): array
    {
        $newFieldsMap = [];
        foreach ($newFieldsData as $fieldData) {
            $newFieldsMap[$fieldData['name']] = $fieldData;
        }
        return $newFieldsMap;
    }

    private function removeFields(array $fieldsToDelete): void
    {
        foreach ($fieldsToDelete as $field) {
            $this->entityManager->remove($field);
        }
    }

    public function getCustomFieldsWithValues(int $itemId): array
    {
        $customFieldsValues = $this->itemRepository->getCustomFieldsWithValues($itemId);
        return $this->customFieldMapper->mapToCustomFieldValues($customFieldsValues);
    }
}