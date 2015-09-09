<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Assert\Assertion;

/**
 * All members have a unique identifier
 */
class Identifier
{
    /** @var string */
    private $id;

    /**
     * @param string $id
     */
    private function __construct($id)
    {
        $this->setId($id);
    }

    /**
     * @param string $id
     */
    private function setId($id)
    {
        Assertion::string($id);
        Assertion::notEmpty($id);

        $this->id = $id;
    }

    /**
     * Identifiers are non-empty strings
     *
     * @param string $id
     * @return Identifier
     */
    public static function fromString($id)
    {
        return new Identifier($id);
    }

    /**
     * Generates a random identifier using `uniqid` function
     *
     * @return Identifier
     */
    public static function any()
    {
        return new Identifier(uniqid());
    }

    /**
     * @param Identifier $id
     * @return bool
     */
    public function equals(Identifier $id)
    {
        return $this->id === $id->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
    }
}
