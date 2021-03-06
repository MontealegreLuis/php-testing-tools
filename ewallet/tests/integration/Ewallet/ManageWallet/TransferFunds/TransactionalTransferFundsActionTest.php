<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Adapters\Doctrine\Application\Services\DoctrineSession;
use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;
use Alice\ThreeMembersWithSameBalanceFixture;
use Application\DomainEvents\EventPublisher;
use DataBuilders\Input;
use Doctrine\WithDatabaseSetup;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Exception;
use PHPUnit\Constraints\ProvidesMoneyConstraints;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use RuntimeException;
use SplFileInfo;

final class TransactionalTransferFundsActionTest extends TestCase
{
    use ProphecyTrait;
    use ProvidesMoneyConstraints;
    use WithDatabaseSetup;

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
        $this->publisher->publish(Argument::any())->willThrow(new RuntimeException());

        try {
            $this->action->transfer($this->threeMxn);
        } catch (Exception $ignore) {
        }

        $this->assertBalanceAmounts($originalBalanceInCents, $this->members->with($this->senderId));
        $this->assertBalanceAmounts($originalBalanceInCents, $this->members->with($this->recipientId));
    }

    /** @before */
    public function let()
    {
        $this->_setupDatabaseSchema(new SplFileInfo(__DIR__ . '/../../../../../'));
        $entityManager = $this->setup->entityManager();
        $fixtures = new ThreeMembersWithSameBalanceFixture($entityManager);
        $fixtures->load();
        $this->members = new MembersRepository($entityManager);
        $this->publisher = $this->prophesize(EventPublisher::class);
        $this->action = new TransactionalTransferFundsAction($this->members, $this->publisher->reveal());
        $this->action->setTransactionalSession(new DoctrineSession($entityManager));
        $this->threeMxn = Input::transferFunds([
            'senderId' => 'XYZ',
            'recipientId' => 'ABC',
            'amount' => 3,
        ]);
        $this->senderId = $this->threeMxn->senderId();
        $this->recipientId = $this->threeMxn->recipientId();
    }

    private TransactionalTransferFundsAction $action;

    private MemberId $recipientId;

    private MemberId $senderId;

    private TransferFundsInput $threeMxn;

    private Members $members;

    private ObjectProphecy $publisher;
}
