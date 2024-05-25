<?php

namespace App\Validator\ConstraintsValidator;

use App\DTO\ItemCreateReq;
use App\Entity\CustomField;
use App\Repository\CustomFieldRepository;
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
        $this->checkValueType($value);
        $customFields = $this->customFieldService->getCustomFields($value->getCollectionId());
        $customFieldValues = $value->getCustomFieldValues();
        $this->processCustomFields($customFields, $customFieldValues, $constraint);
    }

    private function processCustomFields(array $customFields, array $customFieldValues, Constraint $constraint): void
    {
        foreach ($customFields as $customField) {
            $this->checkCustomFieldType($customField);
            $this->validateCustomField($customField, $customFieldValues, $constraint);
        }
    }

    private function validateCustomField($customField, $customFieldValues, $constraint): void
    {
        if ($customField->getIsRequired() && empty($customFieldValues[$customField->getName()])) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{ field }', $customField->getName())->addViolation();
        }
    }

    private function checkConstraintType(Constraint $constraint): void
    {
        if (!$constraint instanceof isCustomFieldsRequired) {
            throw new UnexpectedValueException($constraint, isCustomFieldsRequired::class);
        }
    }

    private function checkValueType(mixed $value): void
    {
        if (!$value instanceof  ItemCreateReq) {
            throw new UnexpectedValueException($value, ItemCreateReq::class);
        }
    }

    private function checkCustomFieldType(mixed $customField): void
    {
        if (!$customField instanceof CustomField) {
            throw new UnexpectedValueException($customField, CustomField::class);
        }
    }
}