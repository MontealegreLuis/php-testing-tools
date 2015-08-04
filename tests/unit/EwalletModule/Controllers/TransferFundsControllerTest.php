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
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Twig_Environment as Twig;

class TransferFundsControllerTest extends TestCase
{
    /** @test */
    function it_should_show_transfer_funds_form()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form->shouldReceive('buildView')->once();
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(Identifier::class))
        ;
        $view = Mockery::mock(Twig::class);
        $view
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
            ->andReturn('')
        ;
        $controller = new TransferFundsController($view, $form, $configuration);

        $response = $controller->showForm(Identifier::fromString('abc'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    function it_should_transfer_funds_from_one_member_to_another()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form->shouldReceive('buildView')->once();
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(Identifier::class))
        ;
        $view = Mockery::mock(Twig::class);
        $view
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
            ->andReturn('')
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
            ->shouldReceive('value')
            ->once()
            ->with('fromMemberId')
            ->andReturn('abc')
        ;
        $request
            ->shouldReceive('value')
            ->once()
            ->with('toMemberId')
            ->andReturn('xyz')
        ;
        $request
            ->shouldReceive('value')
            ->once()
            ->with('amount')
            ->andReturn(100)
        ;
        $request
            ->shouldReceive('isValid')
            ->once()
            ->andReturn(true)
        ;

        $controller = new TransferFundsController(
            $view, $form, $configuration, $useCase
        );

        $response = $controller->transfer($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
