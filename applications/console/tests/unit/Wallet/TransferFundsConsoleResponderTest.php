<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{MemberId, MemberFormatter, MembersRepository};
use Ewallet\DataBuilders\A;
use Ewallet\SymfonyConsole\Commands\TransferFundsCommand;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\{ArrayInput, InputInterface};
use Symfony\Component\Console\Output\BufferedOutput;

class TransferFundsConsoleResponderTest extends TestCase
{
    /** @test */
    function it_shows_error_messages_when_invalid_input_is_provided()
    {
        $responder = new TransferFundsConsoleResponder(
            Mockery::mock(InputInterface::class),
            $output = new BufferedOutput(),
            Mockery::mock(QuestionHelper::class),
            Mockery::mock(MembersRepository::class),
            Mockery::mock(MemberFormatter::class)
        );

        $responder->respondToInvalidTransferInput([
            'senderId' => ['Unknown member'],
            'amount' => ['Amount must not be negative'],
        ], [
            'senderId' => ['This ID does not belong to any known member'],
            'amount' => [-100],
        ]);

        $messages = $output->fetch();

        $this->assertRegexp(
            '/Unknown member/',
            $messages,
            'First error message was not added to the output'
        );
        $this->assertRegexp(
            '/Amount must not be negative/',
            $messages,
            'Second error message was not added to the output'
        );
    }

    /** @test */
    function it_adds_the_member_id_and_the_amount_to_be_transferred_to_the_input()
    {
        $question = Mockery::mock(QuestionHelper::class)
            ->shouldReceive('ask')
            ->twice()
            ->andReturn('LMV', 100)
            ->getMock()
        ;
        $members = Mockery::mock(MembersRepository::class)
            ->shouldReceive('excluding')
            ->once()
            ->andReturn([A::member()->build(), A::member()->build()])
            ->getMock()
        ;
        $definition = (new TransferFundsCommand(
            Mockery::mock(TransferFundsAction::class),
            Mockery::mock(TransferFundsInput::class)
        ))->getDefinition();
        $responder = new TransferFundsConsoleResponder(
            $input = new ArrayInput([], $definition),
            new BufferedOutput(),
            $question,
            $members,
            new MemberFormatter()
        );

        $responder->respondToEnterTransferInformation(MemberId::withIdentity('LMV'));

        $this->assertTrue(
            $input->hasArgument('recipientId'),
            'The ID of the member receiving the funds was not set'
        );
        $this->assertEquals(
            'LMV',
            $input->getArgument('recipientId'),
            'The ID of the member receiving the funds is incorrect'
        );
        $this->assertTrue(
            $input->hasArgument('amount'),
            'The amount to be transferred was not set'
        );
        $this->assertEquals(
            100,
            $input->getArgument('amount'),
            'The amount to be transferred is incorrect'
        );
    }

    /** @test */
    function it_shows_the_statement_for_members_involved_int_the_transaction()
    {
        $responder = new TransferFundsConsoleResponder(
            Mockery::mock(InputInterface::class),
            $output = new BufferedOutput(),
            Mockery::mock(QuestionHelper::class),
            Mockery::mock(MembersRepository::class),
            new MemberFormatter()
        );

        $responder->respondToTransferCompleted(new TransferFundsSummary(
            A::member()->withName('Luis Montealegre')->build(),
            A::member()->withName('Misraim Mendoza')->build()
        ));

        $messages = $output->fetch();

        $this->assertRegexp(
            '/Luis Montealegre/',
            $messages,
            'Member\'s name is missing in the final statement'
        );
        $this->assertRegexp(
            '/Misraim Mendoza/',
            $messages,
            'Member\'s name is missing in the final statement'
        );
    }
}
