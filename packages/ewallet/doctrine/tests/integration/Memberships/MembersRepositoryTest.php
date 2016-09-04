<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Ewallet\ContractTests\Memberships\MembersTest;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

class MembersRepositoryTest extends MembersTest
{
    use ProvidesDoctrineSetup;

    /** @before */
    function generateFixtures()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../config.php');
        $this
            ->_entityManager()
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
        parent::generateFixtures();
    }

    /**
     * @return Members
     */
    protected function membersInstance(): Members
    {
        return $this->_entityManager()->getRepository(Member::class);
    }
}
