<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use Ewallet\Memberships\{MemberId, Member};
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use PHPUnit_Framework_TestCase as TestCase;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

class MembersConfigurationTest extends TestCase
{
    use ProvidesDoctrineSetup;

    /** @test */
    function it_generates_options_excluding_the_member_transferring_funds()
    {
        $senderId = 'ABC';

        $options = $this->configuration->getMembersChoicesExcluding(
            MemberId::withIdentity($senderId)
        );

        $this->assertCount(2, $options);
        $this->assertArrayNotHasKey($senderId, $options);
    }

    /** @before */
    public function createConfiguration()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../config.php');
        $fixture = new ThreeMembersWithSameBalanceFixture($this->_entityManager());
        $fixture->load();

        /** @var \Ewallet\Memberships\MembersRepository $members */
        $members = $this->_repositoryForEntity(Member::class);
        $this->configuration = new MembersConfiguration($members);
    }

    /** @var MembersConfiguration */
    private $configuration;
}
