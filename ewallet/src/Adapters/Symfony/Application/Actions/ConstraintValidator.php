<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Application\Actions;

use Application\Actions\InputValidator;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

abstract class ConstraintValidator implements InputValidator
{
    /** @var array */
    private $inputValues;

    /** @var ConstraintViolationListInterface */
    private $violations;

    protected function __construct(array $values)
    {
        $this->inputValues = $values;
        $this->violations = new ConstraintViolationList();
    }

    /** Is the raw input valid? */
    public function isValid(): bool
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->violations = $validator->validate($this);
        return $this->violations->count() === 0;
    }

    /** @return string[] */
    public function errors(): array
    {
        $errors  = [];
        /** @var \Symfony\Component\Validator\ConstraintViolation $violation */
        foreach ($this->violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }

    /**
     * The original raw input.
     *
     * This is usually shown back to the user through the UI, to provide feedback when validation fails
     */
    public function values(): array
    {
        return $this->inputValues;
    }
}
