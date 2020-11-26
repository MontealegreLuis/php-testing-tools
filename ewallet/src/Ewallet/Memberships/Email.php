<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Webmozart\Assert\Assert;

/**
 * All members receive notifications of their transactions via email
 */
final class Email
{
    private string $address;

    public function __construct(string $address)
    {
        $this->setAddress($address);
    }

    public function address(): string
    {
        return $this->address;
    }

    private function setAddress(string $address): void
    {
        Assert::email($address, "{$address} is not a valid email address");
        $this->address = $address;
    }
}
