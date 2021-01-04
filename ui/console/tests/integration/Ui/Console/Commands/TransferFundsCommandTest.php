<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Alice\ThreeMembersWithSameBalanceFixture;
use Application\InputValidation\InputValidator;
use Doctrine\WithDatabaseSetup;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\MemberFormatter;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @covers \Ewallet\Ui\Console\Commands\TransferFundsCommand
 */
final class TransferFundsCommandTest extends TestCase
{
    use WithDatabaseSetup;

    /** @test */
    function it_transfers_funds_between_members()
    {
        $input = new ArrayInput([
            'senderId' => 'ABC',
            'recipientId' => 'LMN',
            'amount' => 5,
        ]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertMatchesRegularExpression('/Transfer completed successfully!/', $this->output->fetch());
        $this->assertEquals($success = 0, $statusCode);
    }

    /** @test */
    function it_shows_error_messages_when_invalid_input_is_provided()
    {
        $input = new ArrayInput([]);

        $statusCode = $this->command->run($input, $this->output);

        $this->assertMatchesRegularExpression('/invalid-input/', $this->output->fetch());
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

        $this->assertMatchesRegularExpression('/cannot-complete-transfer/', $this->output->fetch());
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

        $this->assertMatchesRegularExpression('/cannot-complete-transfer/', $this->output->fetch());
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

        $this->assertMatchesRegularExpression('/cannot-complete-transfer/', $this->output->fetch());
        $this->assertEquals($success = 1, $statusCode);
    }

    /** @before */
    function let()
    {
        $path = new SplFileInfo(__DIR__ . '/../../../../../');
        $this->_setupDatabaseSchema($path);
        (new ThreeMembersWithSameBalanceFixture($this->setup->entityManager()))->load();
        $action = $this->container->get(TransferFundsAction::class);
        $this->output = new BufferedOutput();
        $console = new TransferFundsConsole($this->output, new MemberFormatter());
        $this->command = new TransferFundsCommand($action, $console, $this->container->get(InputValidator::class));
    }

    private TransferFundsCommand $command;

    private BufferedOutput $output;
}
