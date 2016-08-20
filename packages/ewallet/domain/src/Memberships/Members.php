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
     * @param MemberId $id
     * @return Member
     * @throws UnknownMember
     */
    public function with(MemberId $id): Member;

    /**
     * @param Member $member
     */
    public function add(Member $member);

    /**
     * @param Member $member
     */
    public function update(Member $member);
}
