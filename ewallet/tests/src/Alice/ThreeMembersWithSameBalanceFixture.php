<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Alice;

use Doctrine\ORM\EntityManager;
use Ewallet\Memberships\Member;
use Nelmio\Alice\Loader\NativeLoader;

/**
 * Fixture with 3 members, one with predefined information, 2 random. All of
 * them have a balance of $1000.00 MXN
 */
class ThreeMembersWithSameBalanceFixture
{
    private EntityManager $objectManager;

    public function __construct(EntityManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Cleanup before populating `members` table
     */
    public function load(): void
    {
        $this
            ->objectManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute();

        $loader = new NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../../fixtures/members.yml');

        foreach ($objectSet->getObjects() as $object) {
            if ($object instanceof Member) {
                $this->objectManager->persist($object);
                $this->objectManager->flush($object);
            }
        }
    }
}
