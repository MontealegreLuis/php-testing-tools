<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Zf2\Mail;

use Application\Templating\TemplateEngine;
use DataBuilders\A;
use DateTime;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Zend\Mail\Transport\InMemory;

class TransferFundsZendMailSenderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    function it_notifies_sender_by_email()
    {
        $senderEmail = 'montealegreluis@gmail.com';

        $this->sender->sendFundsTransferredEmail(
            A::member()->withEmail($senderEmail)->build(),
            A::member()->build(),
            Money::MXN(500),
            new DateTime()
        );

        $this->assertEquals(
            $senderEmail,
            $this->transport->getLastMessage()->getTo()->current()->getEmail(),
            'Address doesn\'t belong to the member making the transfer'
        );
        $this->assertRegExp(
            '/transfer completed/',
            $this->transport->getLastMessage()->getSubject(),
            'Email\'s subject is wrong'
        );
    }

    /** @test */
    function it_notifies_recipient_by_email()
    {
        $recipientEmail = 'montealegreluis@gmail.com';

        $this->sender->sendDepositReceivedEmail(
            A::member()->build(),
            A::member()->withEmail($recipientEmail)->build(),
            Money::MXN(500),
            new DateTime()
        );

        $this->assertEquals(
            $recipientEmail,
            $this->transport->getLastMessage()->getTo()->current()->getEmail(),
            'Address doesn\'t belong to the member receiving the deposit'
        );
        $this->assertRegExp(
            '/received.*deposit/',
            $this->transport->getLastMessage()->getSubject(),
            'Email\'s subject is wrong'
        );
    }

    /** @before */
    public function configureMailSender(): void
    {
        $this->template = Mockery::mock(TemplateEngine::class);
        $this->transport = new InMemory();
        $this->sender = new TransferFundsZendMailSender(
            $this->template,
            $this->transport
        );
        $this->template
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
        ;
    }

    /** @var TransferFundsZendMailSender */
    private $sender;

    /** @var TemplateEngine */
    private $template;

    /** @var InMemory */
    private $transport;
}
