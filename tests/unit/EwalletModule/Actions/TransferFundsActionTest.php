<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Actions;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsNotifier;
use Ewallet\Wallet\TransferFundsRequest;
use EwalletModule\Bridges\EasyForms\TransferFundsFormResponder;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ResponseInterface;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_should_show_transfer_funds_form()
    {
        $responder = Mockery::mock(TransferFundsFormResponder::class);
        $responder
            ->shouldReceive('respondEnterTransferInformation')
            ->once()
        ;
        $responder
            ->shouldReceive('response')
            ->once()
            ->andReturn(Mockery::type(ResponseInterface::class))
        ;

        $controller = new TransferFundsAction($responder);

        $controller->showForm(Identifier::fromString('abc'));
    }

    /** @test */
    function it_should_transfer_funds_from_one_member_to_another()
    {
        $responder = Mockery::mock(TransferFundsFormResponder::class);
        $responder
            ->shouldReceive('response')
            ->once()
        ;
        $useCase = Mockery::mock(TransferFunds::class);
        $useCase
            ->shouldReceive('transfer')
            ->once()
            ->with(Mockery::type(TransferFundsRequest::class))
        ;
        $useCase
            ->shouldReceive('attach')
            ->once()
            ->with(Mockery::type(TransferFundsNotifier::class))
        ;
        $request = Mockery::mock(FilteredRequest::class);
        $request
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true)
        ;
        $request
            ->shouldReceive('values')
            ->once()
            ->andReturn([
                'fromMemberId' => 'abc', 'toMemberId' => 'xyz', 'amount' => 100
            ])
        ;

        $controller = new TransferFundsAction($responder, $useCase);

        $controller->transfer($request);
    }
}
