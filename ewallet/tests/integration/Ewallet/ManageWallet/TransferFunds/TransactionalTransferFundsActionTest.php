<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Adapters\Doctrine\Application\Services\DoctrineSession;
use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;
use Alice\ThreeMembersWithSameBalanceFixture;
use Application\DomainEvents\EventPublisher;
use Doctrine\DataStorageSetup;
use Exception;
use PHPUnit\Constraints\ProvidesMoneyConstraints;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use RuntimeException;

class TransactionalTransferFundsActionTest extends TestCase
{
    use ProvidesMoneyConstraints;

    /** @test */
    function it_transfers_funds_between_members()
    {
        $withdrawn300Cents = 700;
        $deposited300Cents = 1300;

        $this->action->transfer($this->threeMxn);

        $this->assertBalanceAmounts($withdrawn300Cents, $this->members->with($this->senderId));
        $this->assertBalanceAmounts($deposited300Cents, $this->members->with($this->recipientId));
    }

    /** @test */
    function it_rollbacks_an_incomplete_transfer()
    {
        $originalBalanceInCents = 1000;
        $this
            ->responder
            ->respondToTransferCompleted(Argument::type(TransferFundsSummary::class))
            ->willThrow(RuntimeException::class)
        ;

        try {
            $this->action->transfer($this->threeMxn);
        } catch (Exception $ignore) {
        }

        $this->assertBalanceAmounts($originalBalanceInCents, $this->members->with($this->senderId));
        $this->assertBalanceAmounts($originalBalanceInCents, $this->members->with($this->recipientId));
    }

    /** @before */
    public function configureUseCase(): void
    {
        $setup = new DataStorageSetup(require __DIR__ . '/../../../../../config/config.php');
        $setup->updateSchema();
        $entityManager = $setup->entityManager();

        $fixtures = new ThreeMembersWithSameBalanceFixture($entityManager);
        $fixtures->load();

        $this->members = new MembersRepository($entityManager);

        $this->action = new TransactionalTransferFundsAction($this->members, new EventPublisher());
        $this->action->setTransactionalSession(new DoctrineSession($entityManager));

        $this->responder = $this->prophesize(TransferFundsResponder::class);
        $this->action->attach($this->responder->reveal());

        $this->threeMxn = TransferFundsInput::from([
            'senderId' => 'XYZ',
            'recipientId' => 'ABC',
            'amount' => 3,
        ]);
        $this->senderId = $this->threeMxn->senderId();
        $this->recipientId = $this->threeMxn->recipientId();
    }

    /** @var TransactionalTransferFundsAction Subject under test */
    private $action;

    /** @var \Ewallet\Memberships\MemberId */
    private $recipientId;

    /** @var \Ewallet\Memberships\MemberId */
    private $senderId;

    /** @var TransferFundsResponder */
    private $responder;

    /** @var TransferFundsInput */
    private $threeMxn;

    /** @var \Ewallet\Memberships\Members $members */
    private $members;
}
