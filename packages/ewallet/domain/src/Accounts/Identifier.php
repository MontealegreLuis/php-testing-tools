<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Assert\Assertion;

abstract class Identifier
{
    /** @var string */
    private $value;

    /**
     * @param string $value
     */
    private function __construct($value)
    {
        $this->setId($value);
    }

    /**
     * @param string $value
     */
    private function setId($value)
    {
        Assertion::string($value, "Identifiers should be strings");
        Assertion::notEmpty(trim($value), "An identifier cannot be empty");

        $this->value = $value;
    }

    /**
     * Identifiers are non-empty strings
     *
     * @param string $value
     * @return Identifier
     */
    public static function with($value)
    {
        return new static($value);
    }

    /**
     * Generates a random identifier using `uniqid` function
     *
     * @return Identifier
     */
    public static function any()
    {
        return new static(uniqid());
    }

    /**
     * @param Identifier $anotherId
     * @return bool
     */
    public function equals(Identifier $anotherId)
    {
        return $this->value === $anotherId->value;
    }

    /**
     * @return string
     */
    public function value()
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
