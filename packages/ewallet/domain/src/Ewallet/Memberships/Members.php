<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

/**
 * Handle the persistence of members information
 */
interface Members
{
    /**
     * @throws UnknownMember If the member with given identifier cannot be found
     */
    public function with(MemberId $memberId): Member;

    public function add(Member $member): void;

    public function update(Member $member): void;
}
