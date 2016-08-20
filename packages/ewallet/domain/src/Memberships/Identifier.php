<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Assert\Assertion;

abstract class Identifier
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    private function __construct(string $value)
    {
        $this->setId(trim($value));
    }

    /**
     * @param string $value
     */
    private function setId(string $value)
    {
        Assertion::notEmpty($value, "An identifier cannot be empty");

        $this->value = $value;
    }

    /**
     * Identifiers are non-empty strings
     *
     * @param string $value
     * @return Identifier
     */
    public static function withIdentity(string $value): Identifier
    {
        return new static($value);
    }

    /**
     * @param Identifier $anotherId
     * @return bool
     */
    public function equals(Identifier $anotherId): bool
    {
        return $this->value === $anotherId->value;
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
