<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Alice\ThreeMembersWithSameBalanceFixture;
use Doctrine\ProvidesDoctrineSetup;
use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\ManageWallet\TransferFundsConsole;
use Ewallet\Memberships\MemberFormatter;
use PHPUnit\Framework\TestCase;
use Ports\Doctrine\Application\Services\DoctrineSession;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

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
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');
        (new ThreeMembersWithSameBalanceFixture($this->_entityManager()))->load();
        $members = new MembersRepository($this->_entityManager());
        $action = new TransactionalTransferFundsAction($members);
        $action->setTransactionalSession(new DoctrineSession($this->_entityManager()));
        $this->input = new ArrayInput([]);
        $this->output = new BufferedOutput();
        $console = new TransferFundsConsole($this->output, new MemberFormatter());
        $this->command = new TransferFundsCommand($action, $console);
    }

    /** @var TransferFundsCommand Subject under test */
    private $command;

    /** @var QuestionHelper */
    private $question;

    /** @var ArrayInput */
    private $input;

    /** @var BufferedOutput */
    private $output;
}
