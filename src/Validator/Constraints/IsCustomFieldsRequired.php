<?php

namespace App\Validator\Constraints;

use App\Validator\ConstraintsValidator\IsCustomFieldsRequiredValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsCustomFieldsRequired extends Constraint
{
    public string $message = 'The field { field } is required.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return isCustomFieldsRequiredValidator::class;
    }

}