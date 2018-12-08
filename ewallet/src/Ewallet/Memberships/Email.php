<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Assert\Assertion;

/**
 * All members receive notifications of their transactions via email
 */
final class Email
{
    /** @var string */
    private $address;

    /**
     * @throws \Assert\AssertionFailedException If the email address is invalid
     */
    public function __construct(string $address)
    {
        $this->setAddress($address);
    }

    public function address(): string
    {
        return $this->address;
    }

    /**
     * The address is validated before setting it
     *
     * @throws \Assert\AssertionFailedException If the email address is invalid
     */
    private function setAddress(string $address): void
    {
        Assertion::email($address, "{$address} is not a valid email address");
        $this->address = $address;
    }
}
