<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

use Ewallet\DataBuilders\A;
use Ewallet\Accounts\{MemberId, InMemoryMembers};
use Ewallet\Wallet\{TransferFunds, TransferFundsSummary};
use Ewallet\Responders\TransferFundsResponder;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_allows_to_enter_transfer_information()
    {
        $responder = Mockery::spy(TransferFundsResponder::class);
        $action = new TransferFundsAction($responder);

        $action->enterTransferInformation(MemberId::with('any'));

        $responder
            ->shouldHaveReceived('respondToEnterTransferInformation')
            ->once()
        ;
    }

    /** @test */
    function it_allows_to_transfer_funds()
    {
        $responder = Mockery::spy(TransferFundsResponder::class);
        $useCase = $this->givenThatMembersAreKnown(
            $fromId = 'abc',
            $toId = 'xyz'
        );
        $request = $this->givenThatValidTransferInformationIsProvided(
            $fromId, $toId, $amount = 100
        );
        $action = new TransferFundsAction($responder, $useCase);

        $action->transfer($request);

        $responder
            ->shouldHaveReceived('respondToTransferCompleted')
            ->once()
            ->with(Mockery::type(TransferFundsSummary::class))
        ;
    }

    /** @test */
    function it_notifies_when_transfer_funds_information_is_invalid()
    {
        $responder = Mockery::spy(TransferFundsResponder::class);
        $useCase = Mockery::spy(TransferFunds::class);
        $request = $this->givenThatNoAmountIsProvided(
            $fromId = 'abc', $toId = 'xyz'
        );
        $action = new TransferFundsAction($responder, $useCase);

        $action->transfer($request);

        $responder
            ->shouldHaveReceived('respondToInvalidTransferInput')
            ->once()
            ->with(Mockery::type('array'), Mockery::type('array'))
        ;

        $useCase->shouldNotHaveReceived('transfer');
    }

    /**
     * @param string $fromMemberId
     * @param string $toMemberId
     * @return TransferFundsInput
     */
    private function givenThatNoAmountIsProvided(
        string $fromMemberId,
        string $toMemberId
    ): TransferFundsInput
    {
        $input = Mockery::mock(TransferFundsInput::class);
        $input
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(false)
        ;
        $input
            ->shouldReceive('errorMessages')
            ->once()
            ->andReturn([
                'amount' => 'No amount was provided',
            ])
        ;
        $input
            ->shouldReceive('values')
            ->once()
            ->andReturn([
                'fromMemberId' => $fromMemberId,
                'toMemberId' => $toMemberId,
            ])
        ;

        return $input;
    }

    /**
     * @param string $fromMemberId
     * @param string $toMemberId
     * @param int $amount
     * @return TransferFundsRequest
     */
    private function givenThatValidTransferInformationIsProvided(
        string $fromMemberId,
        string $toMemberId,
        int $amount
    ): TransferFundsInput
    {
        $input = Mockery::mock(TransferFundsInput::class);
        $input
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true)
        ;
        $input
            ->shouldReceive('values')
            ->once()
            ->andReturn([
                'fromMemberId' => $fromMemberId,
                'toMemberId' => $toMemberId,
                'amount' => $amount
            ])
        ;
        return $input;
    }

    /**
     * @param string $fromMemberId
     * @param string $toMemberId
     * @return TransferFunds
     */
    private function givenThatMembersAreKnown(
        string $fromMemberId,
        string $toMemberId
    ): TransferFunds
    {
        $members = new InMemoryMembers();
        $members->add(
            A::member()->withId($fromMemberId)->withBalance(20000)->build()
        );
        $members->add(A::member()->withId($toMemberId)->build());

        return new TransferFunds($members);
    }
}
