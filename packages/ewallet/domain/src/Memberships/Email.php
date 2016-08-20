<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Assert\Assertion;

/**
 * All members receive notifications of their transactions via email
 */
class Email
{
    /** @var string */
    private $address;

    /**
     * @param string $address A valid e-mail address
     */
    public function __construct(string $address)
    {
        $this->setAddress($address);
    }

    /**
     * The address is validated before setting it
     *
     * @param $address
     */
    protected function setAddress(string $address)
    {
        Assertion::email($address, "{$address} is not a valid email address");
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function address(): string
    {
        return $this->address;
    }
}
