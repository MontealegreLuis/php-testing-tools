<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\Mail;

use DateTime;
use Ewallet\Memberships\MemberInformation;
use Ewallet\ManageWallet\Notifications\TransferFundsEmailSender;
use Ewallet\Templating\TemplateEngine;
use Money\Money;
use Zend\Mail\{Message, Transport\TransportInterface};

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
     * @param MemberInformation $sender
     * @param MemberInformation $recipient
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendFundsTransferredEmail(
        MemberInformation $sender,
        MemberInformation $recipient,
        Money $amount,
        DateTime $occurredOn
    ) {
        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo($sender->email()->address())
            ->setSubject('Funds transfer completed')
            ->setBody($this->template->render('email/transfer.html', [
                'sender' => $sender,
                'recipient' => $recipient,
                'amount' => $amount,
                'occurredOn' => $occurredOn,
            ]))
        ;
        $this->mailTransport->send($message);
    }

    /**
     * @param MemberInformation $sender
     * @param MemberInformation $recipient
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendDepositReceivedEmail(
        MemberInformation $sender,
        MemberInformation $recipient,
        Money $amount,
        DateTime $occurredOn
    ) {
        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo($recipient->email()->address())
            ->setSubject('You have received a deposit')
            ->setBody($this->template->render('email/deposit.html', [
                'sender' => $sender,
                'recipient' => $recipient,
                'amount' => $amount,
                'occurredOn' => $occurredOn,
            ]))
        ;
        $this->mailTransport->send($message);
    }
}
