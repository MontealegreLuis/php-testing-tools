<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Application\InputValidation;

use Application\InputValidation\ErrorMessages;
use Application\InputValidation\ValidationResult;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ConstraintValidationResult implements ValidationResult
{
    private ErrorMessages $errors;

    /** @param ConstraintViolationListInterface<ConstraintViolationInterface> $violations */
    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->errors = new ErrorMessages();
        foreach ($violations as $violation) {
            $this->errors->add($violation->getPropertyPath(), (string) $violation->getMessage());
        }
    }

    public function isValid(): bool
    {
        return $this->errors->isEmpty();
    }

    /** @return mixed[] */
    public function errors(): array
    {
        return $this->errors->messages();
    }
}
