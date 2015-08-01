<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;
use EwalletTestsBridge\ProvidesDoctrineSetup;
use EwalletTestsBridge\ProvidesMoneyConstraint;
use Money\Money;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsTest extends TestCase
{
    use ProvidesDoctrineSetup, ProvidesMoneyConstraint;

    public function setUp()
    {
        $this->_setUpDoctrine();
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
            __DIR__ . '/../../../_data/fixtures/members.yml',
            $this->entityManager
        );

        /** @var Members $members */
        $members = $this->entityManager->getRepository(Member::class);

        $transferBalance = new TransferFunds($members);

        $result = $transferBalance->transfer(
            Identifier::fromString('XYZ'),
            Identifier::fromString('ABC'),
            Money::MXN(300)
        );

        $this->assertBalanceAmounts(700, $result->fromMember()->accountBalance());
        $this->assertBalanceAmounts(1300, $result->toMember()->accountBalance());
    }
}
