<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet\Accounts;

use ContractTests\Ewallet\Accounts\MembersTest;
use Ewallet\Accounts\Members;

class InMemoryMembersTest extends MembersTest
{
    /**
     * @return Members
     */
    protected function membersInstance()
    {
        return new InMemoryMembers();
    }
}
