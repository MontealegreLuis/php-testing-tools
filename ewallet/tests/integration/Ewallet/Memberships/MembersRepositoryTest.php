<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Doctrine\DataStorageSetup;
use Ewallet\ContractTests\Memberships\MembersTest;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;

class MembersRepositoryTest extends MembersTest
{
    /** @before */
    function generateFixtures(): void
    {
        $this->setup = new DataStorageSetup(require __DIR__ . '/../../../../config/config.php');
        $this->setup->updateSchema();
        $this->setup->entityManager()->createQuery('DELETE FROM ' . Member::class)->execute();
        parent::generateFixtures();
    }

    protected function membersInstance(): Members
    {
        return new MembersRepository($this->setup->entityManager());
    }

    /** @var DataStorageSetup */
    private $setup;
}
