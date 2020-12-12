<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Ewallet\Memberships;

use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;
use SplObjectStorage;

final class InMemoryMembers implements Members
{
    private SplObjectStorage $members;

    /**
     * Create an empty collection of members
     */
    public function __construct()
    {
        $this->members = new SplObjectStorage();
    }

    /**
     * @throws UnknownMember If a member with the given ID cannot be found
     */
    public function with(MemberId $memberId): Member
    {
        /** @var Member $member */
        foreach ($this->members as $member) {
            if ($member->hasId($memberId)) {
                return $member;
            }
        }
        throw UnknownMember::identifiedBy($memberId);
    }

    public function save(Member $member): void
    {
        $this->members->attach($member);
    }
}
