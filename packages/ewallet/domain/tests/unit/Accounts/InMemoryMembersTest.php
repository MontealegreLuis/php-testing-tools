<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Ewallet\ContractTests\Accounts\MembersTest;

class InMemoryMembersTest extends MembersTest
{
    /**
     * @return Members
     */
    protected function membersInstance(): Members
    {
        return new InMemoryMembers();
    }
}