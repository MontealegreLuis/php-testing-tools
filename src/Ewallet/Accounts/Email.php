<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Assert\Assertion;

/**
 * All members are notified of its transactions via e-mail
 */
class Email
{
    /** @var string */
    private $address;

    /**
     * @param string $address A valid e-mail address
     */
    public function __construct($address)
    {
        $this->setAddress($address);
    }

    /**
     * The address is validated before setting it
     *
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
