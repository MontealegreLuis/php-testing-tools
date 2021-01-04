<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;
use ContractTests\Ewallet\Memberships\MembersTest;
use Doctrine\WithDatabaseSetup;
use SplFileInfo;

final class MembersRepositoryTest extends MembersTest
{
    use WithDatabaseSetup;

    /** @before */
    function let()
    {
        $this->_setupDatabaseSchema(new SplFileInfo(__DIR__ . '/../../../../'));
        $this->_executeDqlQuery('DELETE FROM ' . Member::class);
    }

    protected function membersInstance(): Members
    {
        return new MembersRepository($this->setup->entityManager());
    }
}
