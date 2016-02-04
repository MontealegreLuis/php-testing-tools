<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use RuntimeException;

/**
 * Exception thrown when a member information cannot be found
 */
class UnknownMember extends RuntimeException
{
    /**
     * @param Identifier $memberId
     * @return UnknownMember
     */
    public static function with(Identifier $memberId)
    {
        return new self("Member with ID {$memberId} cannot be found");
    }
}
