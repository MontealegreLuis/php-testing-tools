<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;
use Ewallet\DataBuilders\A;
use Ewallet\EasyForms\{MembersConfiguration, TransferFundsForm};
use Ewallet\Templating\TemplateEngine;
use Ewallet\Zf2\Diactoros\DiactorosResponseFactory;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsFormResponderTest extends TestCase
{
    /** @test */
    function it_builds_a_response_to_show_the_transfer_form()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form->shouldReceive('buildView')->once();
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(MemberId::class))
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

        $responder->respondToEnterTransferInformation(MemberId::withIdentity('abc'));

        $this->assertEquals(200, $responder->response()->getStatusCode());
    }

    /** @test */
    function it_builds_a_response_to_show_that_a_transfer_was_successful()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(MemberId::class))
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
        $summary = new TransferFundsSummary(
            A::member()->build(),
            A::member()->build()
        );
        $responder = new TransferFundsFormResponder(
            $view, new DiactorosResponseFactory(), $form, $configuration
        );

        $responder->respondToTransferCompleted($summary);

        $this->assertEquals(200, $responder->response()->getStatusCode());
    }

    /** @test */
    function it_builds_a_response_to_show_the_transfer_form_with_validation_errors()
    {
        $configuration = Mockery::mock(MembersConfiguration::class);
        $form = Mockery::mock(TransferFundsForm::class);
        $form
            ->shouldReceive('configure')
            ->once()
            ->with($configuration, Mockery::type(MemberId::class))
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
                'senderId' => 'xyz'
            ]
        );

        $this->assertEquals(200, $responder->response()->getStatusCode());
    }
}
