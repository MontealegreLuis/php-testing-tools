<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\InputValidation;

abstract class InputValues
{
    abstract public function __construct(InputFilter $filter);

    /** @return string[]|null */
    public function validationGroups(): ?array
    {
        return null;
    }

    /** @return mixed[] */
    public function values(): array
    {
        $values = get_object_vars($this);
        unset($values['filter']);
        return $values;
    }
}
