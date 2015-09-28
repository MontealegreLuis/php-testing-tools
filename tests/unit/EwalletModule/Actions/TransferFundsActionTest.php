<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Actions;

use Ewallet\Accounts\Identifier;
use Ewallet\Bridges\Tests\A;
use Ewallet\Wallet\Accounts\InMemoryMembers;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsResponse;
use EwalletModule\Responders\TransferFundsWebResponder;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Diactoros\Response;

class TransferFundsActionTest extends TestCase
{
    /** @test */
    function it_should_show_transfer_funds_form()
    {
        $responder = Mockery::mock(TransferFundsWebResponder::class);
        $responder
            ->shouldReceive('respondEnterTransferInformation')
            ->once()
        ;
        $responder
            ->shouldReceive('response')
            ->once()
            ->andReturn(new Response())
        ;

        $controller = new TransferFundsAction($responder);

        $response = $controller->showForm(Identifier::any());

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    function it_should_transfer_funds_from_one_member_to_another()
    {
        $responder = Mockery::mock(TransferFundsWebResponder::class);
        $responder
            ->shouldReceive('respondTransferCompleted')
            ->once()
            ->with(Mockery::type(TransferFundsResponse::class))
        ;
        $responder
            ->shouldReceive('response')
            ->once()
            ->andReturn(new Response())
        ;
        $members = new InMemoryMembers();
        $members->add(A::member()->withId('abc')->withBalance(20000)->build());
        $members->add(A::member()->withId('xyz')->build());
        $useCase = new TransferFunds($members);
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

        $response = $controller->transfer($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
