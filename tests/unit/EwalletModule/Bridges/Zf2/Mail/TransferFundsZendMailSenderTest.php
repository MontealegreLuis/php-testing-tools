<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\Mail;

use DateTime;
use Ewallet\Bridges\Tests\A;
use EwalletModule\Responders\Web\TemplateEngine;
use Mockery;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mail\Transport\InMemory;

class TransferFundsZendMailSenderTest extends TestCase
{
    /** @test */
    function it_should_send_funds_transferred_email()
    {
        $template = Mockery::mock(TemplateEngine::class);
        $template
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
        ;
        $transport = new InMemory();
        $sender = new TransferFundsZendMailSender($template, $transport);

        $sender->sendFundsTransferredEmail(
            A::member()->withEmail('montealegreluis@gmail.com')->build()->information(),
            A::member()->build()->information(),
            Money::MXN(500),
            new DateTime()
        );

        $this->assertEquals(
            'montealegreluis@gmail.com',
            $transport->getLastMessage()->getTo()->current()->getEmail(),
            'Address doesn\'t belong to the member making the transfer'
        );
        $this->assertRegExp(
            '/transfer completed/',
            $transport->getLastMessage()->getSubject(),
            'Email\'s subject is wrong'
        );
    }

    /** @test */
    function it_should_send_deposit_received_email()
    {
        $template = Mockery::mock(TemplateEngine::class);
        $template
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
        ;
        $transport = new InMemory();
        $sender = new TransferFundsZendMailSender($template, $transport);

        $sender->sendDepositReceivedEmail(
            A::member()->build()->information(),
            A::member()->withEmail('montealegreluis@gmail.com')->build()->information(),
            Money::MXN(500),
            new DateTime()
        );

        $this->assertEquals(
            'montealegreluis@gmail.com',
            $transport->getLastMessage()->getTo()->current()->getEmail(),
            'Address doesn\'t belong to the member receiving the deposit'
        );
        $this->assertRegExp(
            '/received.*deposit/',
            $transport->getLastMessage()->getSubject(),
            'Email\'s subject is wrong'
        );
    }
}
