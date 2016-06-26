<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use Ewallet\Accounts\{MemberId, Member};
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

class MembersConfigurationTest extends TestCase
{
    use ProvidesDoctrineSetup;

    public function setUp()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../config.php');
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
    }

    /** @test */
    function it_generates_options_excluding_the_member_transferring_funds()
    {
        $fixture = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixture->load();

        /** @var MembersRepository $members */
        $members = $this->entityManager->getRepository(Member::class);

        $configuration = new MembersConfiguration($members);

        $options = $configuration->getMembersChoicesExcluding(
            MemberId::with('ABC')
        );

        $this->assertCount(2, $options);
        $this->assertArrayNotHasKey('ABC', $options);
    }
}
