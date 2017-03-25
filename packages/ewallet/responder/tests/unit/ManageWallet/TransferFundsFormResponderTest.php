<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;
use Ewallet\DataBuilders\A;
use Ewallet\EasyForms\{MembersConfiguration, TransferFundsForm};
use Ewallet\Templating\TemplateEngine;
use Ewallet\Zf2\Diactoros\DiactorosResponseFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;

class TransferFundsFormResponderTest extends TestCase
{
    /** @test */
    function it_builds_a_response_to_show_the_transfer_form()
    {
        $this->responder->respondToEnterTransferInformation($this->senderId);

        $response = $this->responder->response();

        $this->assertEquals(self::OK, $response->getStatusCode());
    }

    /** @test */
    function it_builds_a_response_to_show_that_a_transfer_was_completed()
    {
        $summary = new TransferFundsSummary(
            A::member()->withId($this->senderId)->build(),
            A::member()->build()
        );

        $this->responder->respondToTransferCompleted($summary);

        $response = $this->responder->response();

        $this->assertEquals(self::OK, $response->getStatusCode());
    }

    /** @test */
    function it_builds_a_response_to_show_the_transfer_form_with_error_messages()
    {
        $this->responder->respondToInvalidTransferInput(
            $messages = [],
            $values = ['senderId' => $this->senderId->value()]
        );

        $response = $this->responder->response();

        $this->assertEquals(self::OK, $response->getStatusCode());
    }

    /** @before */
    public function configureResponder(): void
    {
        $this->senderId = MemberId::withIdentity('abc');
        $view = $this->prophesize(TemplateEngine::class);
        $view
            ->render(Argument::type('string'), Argument::type('array'))
            ->willReturn('')
        ;
        $configuration = $this->prophesize(MembersConfiguration::class);
        $configuration
            ->getMembersChoicesExcluding($this->senderId)
            ->willReturn([A::member()->build(), A::member()->build()])
        ;
        $this->responder = new TransferFundsFormResponder(
            $view->reveal(),
            new DiactorosResponseFactory(),
            new TransferFundsForm(),
            $configuration->reveal()
        );
    }

    /** @var MemberId */
    private $senderId;

    /** @var TransferFundsFormResponder */
    private $responder;

    const OK = 200;
}
