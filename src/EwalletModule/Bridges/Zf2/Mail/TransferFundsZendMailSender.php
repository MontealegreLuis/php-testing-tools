<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\Mail;

use DateTime;
use Ewallet\Accounts\MemberInformation;
use EwalletModule\Actions\EventSubscribers\TransferFundsEmailSender;
use EwalletModule\Responders\Web\TemplateEngine;
use Money\Money;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class TransferFundsZendMailSender implements TransferFundsEmailSender
{
    /** @var TemplateEngine */
    private $template;

    /** @var TransportInterface */
    private $mailTransport;

    /**
     * @param TemplateEngine $template
     * @param TransportInterface $mailTransport
     */
    public function __construct(
        TemplateEngine $template,
        TransportInterface $mailTransport
    ) {
        $this->template = $template;
        $this->mailTransport = $mailTransport;
    }

    /**
     * @param MemberInformation $fromMember
     * @param MemberInformation $toMember
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendFundsTransferredEmail(
        MemberInformation $fromMember,
        MemberInformation $toMember,
        Money $amount,
        DateTime $occurredOn
    ) {
        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo($fromMember->email()->address())
            ->setSubject('Funds transfer completed')
            ->setBody($this->template->render('email/transfer.html', [
                'fromMember' => $fromMember,
                'toMember' => $toMember,
                'amount' => $amount,
                'occurredOn' => $occurredOn,
            ]))
        ;
        $this->mailTransport->send($message);
    }

    /**
     * @param MemberInformation $fromMember
     * @param MemberInformation $toMember
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendDepositReceivedEmail(
        MemberInformation $fromMember,
        MemberInformation $toMember,
        Money $amount,
        DateTime $occurredOn
    ) {
        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo($toMember->email()->address())
            ->setSubject('You have received a deposit')
            ->setBody($this->template->render('email/deposit.html', [
                'fromMember' => $fromMember,
                'toMember' => $toMember,
                'amount' => $amount,
                'occurredOn' => $occurredOn,
            ]))
        ;
        $this->mailTransport->send($message);
    }
}
