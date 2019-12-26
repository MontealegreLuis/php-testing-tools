<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use ContractTests\Ewallet\Memberships\MembersTest;
use Fakes\Ewallet\Memberships\InMemoryMembers;

class InMemoryMembersTest extends MembersTest
{
    protected function membersInstance(): Members
    {
        return new InMemoryMembers();
    }
}
