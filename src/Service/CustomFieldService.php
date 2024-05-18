<?php

namespace App\Service;

use App\Entity\CustomField;
use App\Entity\UserCollection;

class CustomFieldService
{
    public function addCustomFields(UserCollection $collection, array $fieldsData): void {
        foreach ($fieldsData as $fieldData) {
            $customField = new CustomField();
            $customField->setName($fieldData['name']);
            $customField->setType($fieldData['type']);
            $collection->addCustomField($customField);
        }
    }
}