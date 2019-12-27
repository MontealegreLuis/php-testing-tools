<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Adapters\Doctrine\Application\Services\DoctrineSession;
use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;
use Alice\ThreeMembersWithSameBalanceFixture;
use Application\DomainEvents\EventPublisher;
use Doctrine\DataStorageSetup;
use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\Memberships\MemberFormatter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class TransferFundsCommandTest extends TestCase
{
    /** @test */
    function it_transfers_funds_between_members()
    {
        $input = new ArrayInput([
            'senderId' => 'ABC',
            'recipientId' => 'LMN',
            'amount' => 5,
        ]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertRegExp('/Transfer completed successfully!/', $this->output->fetch());
        $this->assertEquals($success = 0, $statusCode);
    }

    /** @test */
    function it_shows_error_messages_when_invalid_input_is_provided()
    {
        $input = new ArrayInput([]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertRegExp('/FUNDS-422-001/', $this->output->fetch());
        $this->assertEquals($error = 1, $statusCode);
    }

    /** @test */
    function it_shows_error_message_if_sender_cannot_be_found()
    {
        $input = new ArrayInput([
            'senderId' => 'not a known ID',
            'recipientId' => 'LMN',
            'amount' => 5,
        ]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertRegExp('/FUNDS-400-001/', $this->output->fetch());
        $this->assertEquals($success = 1, $statusCode);
    }

    /** @test */
    function it_shows_error_message_if_recipient_cannot_be_found()
    {
        $input = new ArrayInput([
            'senderId' => 'ABC',
            'recipientId' => 'not a known ID',
            'amount' => 5,
        ]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertRegExp('/FUNDS-400-001/', $this->output->fetch());
        $this->assertEquals($success = 1, $statusCode);
    }

    /** @test */
    function it_shows_error_message_if_sender_does_not_have_enough_funds()
    {
        $input = new ArrayInput([
            'senderId' => 'ABC',
            'recipientId' => 'LMN',
            'amount' => 50000, // Not enough funds
        ]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertRegExp('/FUNDS-400-002/', $this->output->fetch());
        $this->assertEquals($success = 1, $statusCode);
    }

    /** @before */
    public function configureCommand(): void
    {
        $setup = new DataStorageSetup(require __DIR__ . '/../../../../config.php');
        $setup->updateSchema();
        (new ThreeMembersWithSameBalanceFixture($setup->entityManager()))->load();
        $members = new MembersRepository($setup->entityManager());
        $action = new TransactionalTransferFundsAction($members, new EventPublisher());
        $action->setTransactionalSession(new DoctrineSession($setup->entityManager()));
        $this->input = new ArrayInput([]);
        $this->output = new BufferedOutput();
        $console = new TransferFundsConsole($this->output, new MemberFormatter());
        $this->command = new TransferFundsCommand($action, $console);
    }

    /** @var TransferFundsCommand Subject under test */
    private $command;

    /** @var ArrayInput */
    private $input;

    /** @var BufferedOutput */
    private $output;
}
