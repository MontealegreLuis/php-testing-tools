<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Member;
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Doctrine2\Application\Services\DoctrineSession;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Exception;
use Mockery;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;
use RuntimeException;

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
    function it_transfers_funds_between_members()
    {
        $fixtures = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixtures->load();

        /** @var Members $members */
        $members = $this->entityManager->getRepository(Member::class);
        $action = Mockery::mock(CanTransferFunds::class)->shouldIgnoreMissing();

        $useCase = new TransferFundsTransactionally($members);
        $useCase->setTransactionalSession(
            new DoctrineSession($this->entityManager)
        );
        $useCase->attach($action);

        $useCase->transfer($request = TransferFundsInformation::from([
            'senderId' => 'XYZ',
            'recipientId' => 'ABC',
            'amount' => 3,
        ]));

        $sender = $members->with($request->senderId());
        $this->assertBalanceAmounts(700, $sender);

        $recipient = $members->with($request->recipientId());
        $this->assertBalanceAmounts(1300, $recipient);
    }

    /** @test */
    function it_rollbacks_an_incomplete_transfer()
    {
        $fixtures = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixtures->load();

        /** @var Members $members */
        $members = $this->entityManager->getRepository(Member::class);

        $useCase = new TransferFundsTransactionally($members);
        $useCase->setTransactionalSession(new DoctrineSession($this->entityManager));
        $useCase->attach(new class() implements CanTransferFunds {
            public function transferCompleted(TransferFundsSummary $summary) {
                throw new RuntimeException('Transfer failed.');
            }
        });

        try {
            $useCase->transfer($request = TransferFundsInformation::from([
                'senderId' => 'XYZ',
                'recipientId' => 'ABC',
                'amount' => 3,
            ]));
        } catch(Exception $ignore) {}

        $sender = $members->with($request->senderId());
        $this->assertBalanceAmounts(1000, $sender); // Should remain equal

        $recipient = $members->with($request->recipientId());
        $this->assertBalanceAmounts(1000, $recipient); // Should not have changed
    }
}
