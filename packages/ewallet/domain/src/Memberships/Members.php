<?php
/**
 * PHP version 7.0
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
     * @throws UnknownMember
     */
    public function with(MemberId $memberId): Member;

    public function add(Member $member);

    public function update(Member $member);
}
