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
use PHPUnit_Framework_TestCase as TestCase;
use RuntimeException;

class TransferFundsTest extends TestCase
{
    use ProvidesDoctrineSetup, ProvidesMoneyConstraints;

    /** @var Members $members */
    private $members;

    public function setUp()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../config.php');
        $fixtures = new ThreeMembersWithSameBalanceFixture($this->_entityManager());
        $fixtures->load();
        $this->members = $this->_entityManager()->getRepository(Member::class);
    }

    /** @test */
    function it_transfers_funds_between_members()
    {
        $action = Mockery::mock(CanTransferFunds::class)->shouldIgnoreMissing();

        $useCase = new TransferFundsTransactionally($this->members);
        $useCase->setTransactionalSession(new DoctrineSession($this->_entityManager()));
        $useCase->attach($action);

        $useCase->transfer($request = TransferFundsInformation::from([
            'senderId' => 'XYZ',
            'recipientId' => 'ABC',
            'amount' => 3,
        ]));

        $sender = $this->members->with($request->senderId());
        $this->assertBalanceAmounts(700, $sender);

        $recipient = $this->members->with($request->recipientId());
        $this->assertBalanceAmounts(1300, $recipient);
    }

    /** @test */
    function it_rollbacks_an_incomplete_transfer()
    {
        $useCase = new TransferFundsTransactionally($this->members);
        $useCase->setTransactionalSession(new DoctrineSession($this->_entityManager()));
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

        $sender = $this->members->with($request->senderId());
        $this->assertBalanceAmounts(1000, $sender); // Should remain equal

        $recipient = $this->members->with($request->recipientId());
        $this->assertBalanceAmounts(1000, $recipient); // Should not have changed
    }
}
