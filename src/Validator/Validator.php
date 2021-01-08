<?php

namespace App\Validator;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Validator
{
    /**
     * @param ConstraintViolationListInterface $errors
     * @return bool
     */
    #[Pure] public function hasError(ConstraintViolationListInterface $errors): bool
    {
        return count($errors) > 0;
    }

    /**
     * @param ConstraintViolationListInterface $errors
     * @return array
     */
    #[Pure] public function getMessage(ConstraintViolationListInterface $errors): array
    {
        $newErrors = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $newErrors[$error->getPropertyPath()] = $error->getMessage();
        }
        return $newErrors;
    }
}