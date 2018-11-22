<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\TransferFunds;

use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Doctrine\ProvidesDoctrineSetup;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Exception;
use PHPUnit\Framework\TestCase;
use Ports\Doctrine\Application\Services\DoctrineSession;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;
use Prophecy\Argument;
use RuntimeException;

class TransactionalTransferFundsActionTest extends TestCase
{
    use ProvidesDoctrineSetup, ProvidesMoneyConstraints;

    /** @test */
    function it_transfers_funds_between_members()
    {
        $withdrawn300Cents = 700;
        $deposited300Cents = 1300;

        $this->useCase->transfer($this->threeMxn);

        $this->assertBalanceAmounts($withdrawn300Cents, $this->members->with($this->senderId));
        $this->assertBalanceAmounts($deposited300Cents, $this->members->with($this->recipientId));
    }

    /** @test */
    function it_rollbacks_an_incomplete_transfer()
    {
        $originalBalanceInCents = 1000;
        $this
            ->action
            ->respondToTransferCompleted(Argument::type(TransferFundsSummary::class))
            ->willThrow(RuntimeException::class)
        ;

        try {
            $this->useCase->transfer($this->threeMxn);
        } catch(Exception $ignore) {}

        $this->assertBalanceAmounts($originalBalanceInCents, $this->members->with($this->senderId));
        $this->assertBalanceAmounts($originalBalanceInCents, $this->members->with($this->recipientId));
    }

    /** @before */
    public function configureUseCase(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');

        $fixtures = new ThreeMembersWithSameBalanceFixture($this->_entityManager());
        $fixtures->load();

        $this->members = new MembersRepository($this->_entityManager());

        $this->useCase = new TransactionalTransferFundsAction($this->members);
        $this->useCase->setTransactionalSession(new DoctrineSession($this->_entityManager()));

        $this->action = $this->prophesize(TransferFundsResponder::class);
        $this->useCase->attach($this->action->reveal());

        $this->threeMxn = TransferFundsInput::from([
            'senderId' => 'XYZ',
            'recipientId' => 'ABC',
            'amount' => 3,
        ]);
        $this->senderId = $this->threeMxn->senderId();
        $this->recipientId = $this->threeMxn->recipientId();
    }

    /** @var TransactionalTransferFundsAction Subject under test */
    private $useCase;

    /** @var \Ewallet\Memberships\MemberId */
    private $recipientId;

    /** @var \Ewallet\Memberships\MemberId */
    private $senderId;

    /** @var TransferFundsResponder */
    private $action;

    /** @var TransferFundsInput */
    private $threeMxn;

    /** @var \Ewallet\Memberships\Members $members */
    private $members;
}
