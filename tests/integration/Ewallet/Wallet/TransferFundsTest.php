<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Member;
use Ewallet\Accounts\Members;
use Ewallet\Bridges\Tests\ProvidesDoctrineSetup;
use Mockery;
use Nelmio\Alice\Fixtures;
use PHPUnit\Constraints\ProvidesMoneyConstraints;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsTest extends TestCase
{
    use ProvidesDoctrineSetup, ProvidesMoneyConstraints;

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
        $notifier = Mockery::mock(TransferFundsNotifier::class)->shouldIgnoreMissing();

        $transferBalance = new TransferFunds($members);
        $transferBalance->attach($notifier);

        $transferBalance->transfer($request = TransferFundsRequest::from([
            'fromMemberId' => 'XYZ',
            'toMemberId' => 'ABC',
            'amount' => 3,
        ]));

        $fromMember = $members->with($request->fromMemberId());
        $this->assertBalanceAmounts(700, $fromMember);

        $toMember = $members->with($request->toMemberId());
        $this->assertBalanceAmounts(1300, $toMember);
    }
}
