<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Assert\Assertion;

class Email
{
    /** @var string */
    private $address;

    /**
     * @param string $address
     */
    public function __construct($address)
    {
        $this->setAddress($address);
    }

    /**
     * @param $address
     */
    protected function setAddress($address)
    {
        Assertion::email($address);
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->address;
    }
}
