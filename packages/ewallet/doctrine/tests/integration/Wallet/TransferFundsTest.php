<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Member;
use Ewallet\Doctrine2\Application\Services\DoctrineSession;
use Ewallet\Fakes\FakeNotifier;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Ewallet\TestHelpers\ProvidesDoctrineSetup;
use Exception;
use Mockery;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsTest extends TestCase
{
    use ProvidesDoctrineSetup, ProvidesMoneyConstraints;

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
    function it_should_transfer_funds_between_members()
    {
        Fixtures::load(
            __DIR__ . '/../../fixtures/members.yml',
            $this->entityManager
        );

        /** @var Members $members */
        $members = $this->entityManager->getRepository(Member::class);
        $notifier = Mockery::mock(TransferFundsNotifier::class)->shouldIgnoreMissing();

        $useCase = new TransferFundsTransactionally($members);
        $useCase->setTransactionalSession(
            new DoctrineSession($this->entityManager)
        );
        $useCase->attach($notifier);

        $useCase->transfer($request = TransferFundsInformation::from([
            'fromMemberId' => 'XYZ',
            'toMemberId' => 'ABC',
            'amount' => 3,
        ]));

        $fromMember = $members->with($request->fromMemberId());
        $this->assertBalanceAmounts(700, $fromMember);

        $toMember = $members->with($request->toMemberId());
        $this->assertBalanceAmounts(1300, $toMember);
    }

    /** @test */
    function it_should_rollback_an_incomplete_transfer()
    {
        Fixtures::load(
            __DIR__ . '/../../fixtures/members.yml',
            $this->entityManager
        );

        /** @var Members $members */
        $members = $this->entityManager->getRepository(Member::class);

        $useCase = new TransferFundsTransactionally($members);
        $useCase->setTransactionalSession(
            new DoctrineSession($this->entityManager)
        );
        $useCase->attach(new FakeNotifier()); // Throw exception before completing transfer

        try {
            $useCase->transfer($request = TransferFundsInformation::from([
                'fromMemberId' => 'XYZ',
                'toMemberId' => 'ABC',
                'amount' => 3,
            ]));
        } catch(Exception $ignore) {}

        $fromMember = $members->with($request->fromMemberId());
        $this->assertBalanceAmounts(1000, $fromMember); // Should remain equal

        $toMember = $members->with($request->toMemberId());
        $this->assertBalanceAmounts(1000, $toMember); // Should not have changed
    }
}
