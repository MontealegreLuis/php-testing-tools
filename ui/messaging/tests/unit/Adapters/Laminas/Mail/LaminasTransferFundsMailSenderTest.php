<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Adapters\Laminas\Mail;

use Application\Templating\TemplateEngine;
use DataBuilders\A;
use DateTime;
use Laminas\Mail\Transport\InMemory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class LaminasTransferFundsMailSenderTest extends TestCase
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
        $this->assertMatchesRegularExpression(
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
        $this->assertMatchesRegularExpression(
            '/received.*deposit/',
            $this->transport->getLastMessage()->getSubject(),
            'Email\'s subject is wrong'
        );
    }

    /** @before */
    public function let()
    {
        $this->template = Mockery::mock(TemplateEngine::class);
        $this->transport = new InMemory();
        $this->sender = new LaminasTransferFundsMailSender($this->template, $this->transport);
        $this->template
            ->shouldReceive('render')
            ->once()
            ->with(Mockery::type('string'), Mockery::type('array'))
        ;
    }

    /** @var LaminasTransferFundsMailSender */
    private $sender;

    /** @var TemplateEngine */
    private $template;

    /** @var InMemory */
    private $transport;
}
