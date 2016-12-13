<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{MemberId, MemberFormatter, MembersRepository};
use Ewallet\DataBuilders\A;
use Ewallet\SymfonyConsole\Commands\TransferFundsCommand;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Question\Question;

class TransferFundsConsoleResponderTest extends TestCase
{
    /** @test */
    function it_shows_error_messages_when_invalid_input_is_provided()
    {
        $messages = [
            'senderId' => ['Unknown member'],
            'amount' => ['Amount must not be negative'],
        ];
        $values = [
            'senderId' => ['This ID does not belong to any known member'],
            'amount' => [-100],
        ];

        $this->responder->respondToInvalidTransferInput($messages, $values);

        $errors = $this->output->fetch();
        $this->assertRegExp(
            '/Unknown member/',
            $errors,
            'First error message was not added to the output'
        );
        $this->assertRegExp(
            '/Amount must not be negative/',
            $errors,
            'Second error message was not added to the output'
        );
    }

    /** @test */
    function it_adds_the_member_id_and_the_amount_to_be_transferred_to_the_input()
    {
        $senderId = MemberId::withIdentity('A sender ID');
        $recipientId = 'A recipient ID';
        $amountInMxn = 100;
        $this
            ->question
            ->ask($this->input, $this->output, Argument::type(Question::class))
            ->willReturn($recipientId, $amountInMxn)
        ;
        $this->members->excluding($senderId)->willReturn([
            A::member()->withId($recipientId)->build()
        ]);

        $this->responder->respondToEnterTransferInformation($senderId);

        $this->assertTrue(
            $this->input->hasArgument('recipientId'),
            'The ID of the member receiving the funds was not set'
        );
        $this->assertEquals(
            $recipientId,
            $this->input->getArgument('recipientId'),
            'The ID of the member receiving the funds is incorrect'
        );
        $this->assertTrue(
            $this->input->hasArgument('amount'),
            'The amount to be transferred was not set'
        );
        $this->assertEquals(
            $amountInMxn,
            $this->input->getArgument('amount'),
            'The amount to be transferred is incorrect'
        );
    }

    /** @test */
    function it_shows_the_statement_for_members_involved_in_the_transaction()
    {
        $sender = A::member()->named('Luis Montealegre')->build();
        $recipient = A::member()->named('Misraim Mendoza')->build();

        $this->responder->respondToTransferCompleted(new TransferFundsSummary(
            $sender,
            $recipient
        ));

        $messages = $this->output->fetch();
        $this->assertRegExp(
            '/Luis Montealegre/',
            $messages,
            'Sender\'s name is missing in the final statement'
        );
        $this->assertRegExp(
            '/Misraim Mendoza/',
            $messages,
            'Recipient\'s name is missing in the final statement'
        );
    }

    /** @before */
    public function configureResponder()
    {
        $command = new TransferFundsCommand(
            $this->prophesize(TransferFundsAction::class)->reveal(),
            $this->prophesize(TransferFundsInput::class)->reveal()
        );
        $this->input = new ArrayInput([], $command->getDefinition());
        $this->members = $this->prophesize(MembersRepository::class);
        $this->question = $this->prophesize(QuestionHelper::class);
        $this->output = new BufferedOutput();
        $this->responder = new TransferFundsConsoleResponder(
            $this->input,
            $this->members->reveal(),
            new TransferFundsConsole(
                $this->input,
                $this->output,
                $this->question->reveal(),
                new MemberFormatter()
            )
        );
    }

    /** @var TransferFundsConsoleResponder subject under test */
    private $responder;

    /** @var ArrayInput */
    private $input;

    /** @var MembersRepository */
    private $members;

    /** @var QuestionHelper */
    private $question;

    /** @var BufferedOutput */
    private $output;

}
