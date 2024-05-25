<?php

namespace App\Service;

use App\Entity\CustomField;
use App\Entity\UserCollection;
use App\Repository\CustomFieldRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomFieldService
{

    public function __construct(private EntityManagerInterface $entityManager,
                                private CustomFieldRepository $customFieldRepository)
    {
    }

    public function addCustomFields(UserCollection $collection, array $fieldsData): void {
        foreach ($fieldsData as $fieldData) {
            $customField = new CustomField();
            $customField->setName($fieldData['name']);
            $customField->setType($fieldData['type']);
            $customField->setIsRequired($fieldData['isRequired']);
            $collection->addCustomField($customField);
        }
    }

    public function getCustomFields(int $collectionId): array {
        return $this->customFieldRepository->findBy(['collection' => $collectionId]);
    }

    public function updateCustomFields(UserCollection $currentFields, array $newFieldsData): void
    {
        $currentFieldsMap = $this->mapCurrentFields($currentFields);
        $newFieldsMap = $this->mapNewFieldsData($newFieldsData);
        $this->removeFields(array_diff_key($currentFieldsMap, $newFieldsMap));
        $this->addCustomFields($currentFields, array_diff_key($newFieldsMap, $currentFieldsMap));
        $this->entityManager->flush();
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
}