<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Bridges\Doctrine2\Accounts;

use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;
use Ewallet\ContractTests\Accounts\MembersTest;
use TestHelpers\Bridges\ProvidesDoctrineSetup;

class MembersRepositoryTest extends MembersTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures()
    {
        $this->_setUpDoctrine();
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
        parent::generateFixtures();
    }

    /**
     * @return Members
     */
    protected function membersInstance()
    {
        return $this->entityManager->getRepository(Member::class);
    }
}
