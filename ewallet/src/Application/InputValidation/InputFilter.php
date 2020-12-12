<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\InputValidation;

interface InputFilter
{
    public function trim(string $key): ?string;

    public function integer(string $key, int $default = null): ?int;
}
