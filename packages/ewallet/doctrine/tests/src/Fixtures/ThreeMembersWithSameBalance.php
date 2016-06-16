<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

/**
 * Fixture with 3 members, one with predefined information, 2 random. All of
 * them have a balance of $1000.00 MXN
 */
class ThreeMembersWithSameBalance
{
    /** @var ObjectManager */
    private $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }


    public function load()
    {
        Fixtures::load(
            __DIR__ . '/../../fixtures/members.yml',
            $this->objectManager
        );
    }
}
