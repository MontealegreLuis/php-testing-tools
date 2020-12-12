<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Application\InputValidation;

use Application\InputValidation\InputValidator;
use Application\InputValidation\InputValues;
use Application\InputValidation\ValidationResult;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ConstraintValidator implements InputValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate(InputValues $values): ValidationResult
    {
        return new ConstraintValidationResult($this->validator->validate($values, null, $values->validationGroups()));
    }
}
