<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsRequest;
use Ewallet\Wallet\TransferFundsResult;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ResponseInterface;

class TransferFundsControllerTest extends TestCase
{
    /** @test */
    function it_should_show_transfer_funds_form()
    {
        $responder = Mockery::mock(TransferFundsResponder::class);
        $responder
            ->shouldReceive('transferFundsFormResponse')
            ->once()
            ->andReturn(Mockery::type(ResponseInterface::class))
        ;
        $controller = new TransferFundsController($responder);

        $controller->showForm(Identifier::fromString('abc'));
    }

    /** @test */
    function it_should_transfer_funds_from_one_member_to_another()
    {
        $responder = Mockery::mock(TransferFundsResponder::class);
        $responder
            ->shouldReceive('successfulTransferResponse')
            ->once()
            ->andReturn(Mockery::type(ResponseInterface::class))
        ;
        $useCase = Mockery::mock(TransferFunds::class);
        $useCase
            ->shouldReceive('transfer')
            ->once()
            ->with(Mockery::type(TransferFundsRequest::class))
            ->andReturn(
                Mockery::mock(TransferFundsResult::class)->shouldIgnoreMissing()
            )
        ;
        $request = Mockery::mock(FilteredRequest::class);
        $request
            ->shouldReceive('values')
            ->once()
            ->andReturn([
                'fromMemberId' => 'abc', 'toMemberId' => 'xyz', 'amount' => 100
            ])
        ;
        $request
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true)
        ;

        $controller = new TransferFundsController($responder, $useCase);

        $controller->transfer($request);
    }
}
