<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;
use Twig_Environment as Twig;

class TransferFundsResponderTest extends TestCase
{
    /** @test */
    function it_should_build_a_response_to_show_the_transfer_form()
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
        $responder = new TransferFundsResponder($view, $form, $configuration);

        $response = $responder->transferFundsFormResponse(Identifier::fromString('abc'));

        $this->assertEquals(200, $response->getStatusCode());
    }

    function it_should_build_a_response_to_show_that_a_transfer_was_succesful()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
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
        $result = Mockery::mock(TransferFundsResult::class)->shouldIgnoreMissing();
        $responder = new TransferFundsResponder($view, $form, $configuration);

        $response = $responder->successfulTransferResponse($result);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
