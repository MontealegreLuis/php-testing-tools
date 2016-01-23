<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders;

use Ewallet\Accounts\Identifier;
use Ewallet\DataBuilders\A;
use Ewallet\Wallet\TransferFundsResult;
use Ewallet\EasyForms\TransferFundsFormResponder;
use Ewallet\EasyForms\MembersConfiguration;
use Ewallet\EasyForms\TransferFundsForm;
use Ewallet\Responders\Web\TemplateEngine;
use Ewallet\Zf2\Diactoros\DiactorosResponseFactory;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsFormResponderTest extends TestCase
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
        $view = Mockery::mock(TemplateEngine::class);
        $view
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
            ->andReturn('')
        ;
        $responder = new TransferFundsFormResponder(
            $view, new DiactorosResponseFactory(), $form, $configuration
        );

        $responder->respondToEnterTransferInformation(Identifier::with('abc'));

        $this->assertEquals(200, $responder->response()->getStatusCode());
    }

    /** @test */
    function it_should_build_a_response_to_show_that_a_transfer_was_successful()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(Identifier::class))
        ;
        $form
            ->shouldReceive('buildView')
            ->once()
        ;
        $view = Mockery::mock(TemplateEngine::class);
        $view
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
            ->andReturn('')
        ;
        $result = new TransferFundsResult(
            A::member()->build(),
            A::member()->build()
        );
        $responder = new TransferFundsFormResponder(
            $view, new DiactorosResponseFactory(), $form, $configuration
        );

        $responder->respondToTransferCompleted($result);

        $this->assertEquals(200, $responder->response()->getStatusCode());
    }

    /** @test */
    function it_should_build_a_response_to_show_the_transfer_form_with_validation_errors()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(Identifier::class))
        ;
        $form->shouldReceive('buildView')->once();
        $form
            ->shouldReceive('submit')
            ->once()
            ->with(Mockery::type('array'))
        ;
        $form
            ->shouldReceive('setErrorMessages')
            ->once()
            ->with(Mockery::type('array'))
        ;
        $view = Mockery::mock(TemplateEngine::class);
        $view
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
            ->andReturn('')
        ;
        $responder = new TransferFundsFormResponder(
            $view, new DiactorosResponseFactory(), $form, $configuration
        );

        $responder->respondToInvalidTransferInput(
            $messages = [],
            $values = [
                'fromMemberId' => 'xyz'
            ]
        );

        $this->assertEquals(200, $responder->response()->getStatusCode());
    }
}
