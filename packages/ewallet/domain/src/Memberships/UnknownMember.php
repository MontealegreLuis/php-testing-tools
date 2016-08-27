<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use RuntimeException;

/**
 * Exception thrown when a member information cannot be found
 */
class UnknownMember extends RuntimeException
{
    /**
     * @param MemberId $memberId
     * @return UnknownMember
     */
    public static function identifiedBy(MemberId $memberId): UnknownMember
    {
        return new self("Member with ID {$memberId->value()} cannot be found");
    }
}
