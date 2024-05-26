<?php

namespace App\Validator\ConstraintsValidator;

use App\DTO\ItemCreateReq;
use App\Entity\CustomField;
use App\Service\CustomFieldService;
use App\Validator\Constraints\IsCustomFieldsRequired;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsCustomFieldsRequiredValidator extends ConstraintValidator
{
    public function __construct(private CustomFieldService  $customFieldService)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        $this->checkConstraintType($constraint);
        $value = $this->checkValueType($value);
        if(empty($value->getCustomFieldValues())) return;
        $customFields = $this->customFieldService->getCustomFields($value->getCollectionId());
        $this->processCustomFields($customFields, $value->getCustomFieldValues(), $constraint);
    }

    private function processCustomFields(array $customFields, array $customFieldValues, Constraint $constraint): void
    {
        foreach ($customFields as $customField) {
            $this->validateCustomField($customField, $customFieldValues, $constraint);
        }
    }

    private function validateCustomField($customField, $customFieldValues, $constraint): void
    {
        $customField = $this->checkCustomFieldType($customField);
        if ($customField->getIsRequired() && empty($customFieldValues[$customField->getName()])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{ field }', $customField->getName())->addViolation();
        }
    }

    private function checkConstraintType(Constraint $constraint): void
    {
        if (!$constraint instanceof isCustomFieldsRequired) {
            throw new UnexpectedValueException($constraint, isCustomFieldsRequired::class);
        }
    }

    private function checkValueType(mixed $value): ItemCreateReq
    {
        if (!$value instanceof  ItemCreateReq) {
            throw new UnexpectedValueException($value, ItemCreateReq::class);
        }
        return $value;
    }

    private function checkCustomFieldType(mixed $customField): CustomField
    {
        if (!$customField instanceof CustomField) {
            throw new UnexpectedValueException($customField, CustomField::class);
        }
        return $customField;
    }
}