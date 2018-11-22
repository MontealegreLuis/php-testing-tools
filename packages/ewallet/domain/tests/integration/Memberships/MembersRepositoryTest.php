<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Ewallet\ContractTests\Memberships\MembersTest;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;

class MembersRepositoryTest extends MembersTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../config.php');
        $this
            ->_entityManager()
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
        parent::generateFixtures();
    }

    protected function membersInstance(): Members
    {
        return new MembersRepository($this->_entityManager());
    }
}
