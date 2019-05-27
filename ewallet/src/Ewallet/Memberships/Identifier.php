<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Assert\Assertion;

abstract class Identifier
{
    /** @var string */
    private $value;

    public function equals(Identifier $anotherId): bool
    {
        return $this->value === $anotherId->value;
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /** @throws \Assert\AssertionFailedException If an empty identifier is given */
    public function __construct(string $value)
    {
        $this->setId(trim($value));
    }

    /** @throws \Assert\AssertionFailedException If an empty identifier is given */
    private function setId(string $value): void
    {
        Assertion::notEmpty($value, 'An identifier cannot be empty');

        $this->value = $value;
    }
}
