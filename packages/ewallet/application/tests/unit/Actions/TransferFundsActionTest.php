<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

use Ewallet\DataBuilders\A;
use Ewallet\Accounts\MemberId;
use Ewallet\Wallet\Accounts\InMemoryMembers;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsResult;
use Ewallet\Responders\TransferFundsResponder;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_should_allow_to_enter_transfer_information()
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
    function it_should_allow_to_transfer_funds()
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
            ->with(Mockery::type(TransferFundsResult::class))
        ;
    }

    /** @test */
    function it_should_notify_when_transfer_funds_information_is_invalid()
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
     * @return TransferFundsRequest
     */
    private function givenThatNoAmountIsProvided($fromMemberId, $toMemberId)
    {
        $request = Mockery::mock(TransferFundsRequest::class);
        $request
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(false)
        ;
        $request
            ->shouldReceive('errorMessages')
            ->once()
            ->andReturn([
                'amount' => 'No amount was provided',
            ])
        ;
        $request
            ->shouldReceive('values')
            ->once()
            ->andReturn([
                'fromMemberId' => $fromMemberId,
                'toMemberId' => $toMemberId,
            ])
        ;
        $request
            ->shouldReceive('value')
            ->with('fromMemberId')
            ->once()
            ->andReturn($fromMemberId)
        ;

        return $request;
    }

    /**
     * @param string $fromMemberId
     * @param string $toMemberId
     * @param integer $amount
     * @return TransferFundsRequest
     */
    private function givenThatValidTransferInformationIsProvided(
        $fromMemberId,
        $toMemberId,
        $amount
    ) {
        $request = Mockery::mock(TransferFundsRequest::class);
        $request
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true)
        ;
        $request
            ->shouldReceive('values')
            ->once()
            ->andReturn([
                'fromMemberId' => $fromMemberId,
                'toMemberId' => $toMemberId,
                'amount' => $amount
            ])
        ;
        return $request;
    }

    /**
     * @param string $fromMemberId
     * @param string $toMemberId
     * @return TransferFunds
     */
    private function givenThatMembersAreKnown($fromMemberId, $toMemberId)
    {
        $members = new InMemoryMembers();
        $members->add(
            A::member()->withId($fromMemberId)->withBalance(20000)->build()
        );
        $members->add(A::member()->withId($toMemberId)->build());

        return new TransferFunds($members);
    }
}
