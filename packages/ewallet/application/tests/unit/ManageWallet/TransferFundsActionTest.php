<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\DataBuilders\A;
use Ewallet\Memberships\{MemberId, InMemoryMembers};
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_allows_to_enter_transfer_information()
    {
        $responder = Mockery::spy(TransferFundsResponder::class);
        $action = new TransferFundsAction($responder);

        $action->enterTransferInformation(MemberId::withIdentity('any'));

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
     * @param string $senderId
     * @param string $recipientId
     * @return TransferFundsInput
     */
    private function givenThatNoAmountIsProvided(
        string $senderId,
        string $recipientId
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
                'senderId' => $senderId,
                'recipientId' => $recipientId,
            ])
        ;

        return $input;
    }

    /**
     * @param string $senderId
     * @param string $recipientId
     * @param int $amount
     * @return TransferFundsRequest
     */
    private function givenThatValidTransferInformationIsProvided(
        string $senderId,
        string $recipientId,
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
                'senderId' => $senderId,
                'recipientId' => $recipientId,
                'amount' => $amount
            ])
        ;
        return $input;
    }

    /**
     * @param string $senderId
     * @param string $recipientId
     * @return TransferFunds
     */
    private function givenThatMembersAreKnown(
        string $senderId,
        string $recipientId
    ): TransferFunds
    {
        $members = new InMemoryMembers();
        $members->add(
            A::member()->withId($senderId)->withBalance(20000)->build()
        );
        $members->add(A::member()->withId($recipientId)->build());

        return new TransferFunds($members);
    }
}
