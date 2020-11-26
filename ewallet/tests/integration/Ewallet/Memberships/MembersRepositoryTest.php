<?php
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;
use ContractTests\Ewallet\Memberships\MembersTest;
use Doctrine\DataStorageSetup;

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
