<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use SplObjectStorage;

class InMemoryMembers implements Members
{
    /** @var SplObjectStorage */
    private $members;

    /**
     * Create an empty collection of members
     */
    public function __construct()
    {
        $this->members = new SplObjectStorage();
    }

    /**
     * @param MemberId $id
     * @return Member
     * @throws UnknownMember
     */
    public function with(MemberId $id): Member
    {
        /** @var Member $member */
        foreach ($this->members as $member) {
            if ($member->information()->id()->equals($id)) {
                return $member;
            }
        }
        throw UnknownMember::with($id);
    }

    /**
     * @param Member $member
     */
    public function add(Member $member)
    {
        $this->members->attach($member);
    }

    /**
     * @param Member $member
     */
    public function update(Member $member)
    {
        $this->members->attach($member);
    }
}
