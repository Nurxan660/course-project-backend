<?php

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    /**
     * @throws ValidationException
     */
    public function validate($object): void {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            throw new ValidationException((string) $errors);
        }
    }
}