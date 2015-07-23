<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;
use Money\Money;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsTest extends TestCase
{
    /** @var EntityManager */
    private $entityManager;

    public function setUp()
    {
        $options = require __DIR__ . '/../../../../app/config.php';

        $configuration = Setup::createXMLMetadataConfiguration(
            $options['doctrine']['mapping_dirs'],
            $options['doctrine']['dev_mode'],
            $options['doctrine']['proxy_dir']
        );
        $this->entityManager = EntityManager::create(
            $options['doctrine']['connection'], $configuration
        );

        $platform = $this->entityManager->getConnection()->getDatabasePlatform();
        foreach ($options['doctrine']['types'] as $type => $class) {
            !Type::hasType($type) && Type::addType($type, $class);
            $platform->registerDoctrineTypeMapping($type, $type);
        }

        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
    }

    /** @test */
    function it_should_transfer_funds_between_members()
    {
        Fixtures::load(
            __DIR__ . '/../../fixtures/members.yml', $this->entityManager
        );

        /** @var Members $members */
        $members = $this->entityManager->getRepository(Member::class);

        $transferBalance = new TransferFunds($members);

        $result = $transferBalance->transfer(
            Identifier::fromString('XYZ'),
            Identifier::fromString('ABC'),
            Money::MXN(300)
        );

        $this->assertEquals(
            700, $result->fromMember()->accountBalance()->getAmount()
        );
        $this->assertEquals(
            1300, $result->toMember()->accountBalance()->getAmount()
        );
    }
}
