<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet\Accounts;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;
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
     * @param Identifier $id
     * @return Member | null
     */
    public function with(Identifier $id)
    {
        /** @var Member $member */
        foreach ($this->members as $member) {
            if ($member->information()->id()->equals($id)) {
                return $member;
            }
        }
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
