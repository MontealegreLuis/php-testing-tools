<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Zf2\Mail;

use Application\Templating\TemplateEngine;
use DateTime;
use Ewallet\ManageWallet\Notifications\TransferFundsEmailSender;
use Ewallet\Memberships\Member;
use Money\Money;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class TransferFundsZendMailSender implements TransferFundsEmailSender
{
    /** @var TemplateEngine */
    private $template;

    /** @var TransportInterface */
    private $mailTransport;

    public function __construct(TemplateEngine $template, TransportInterface $mailTransport)
    {
        $this->template = $template;
        $this->mailTransport = $mailTransport;
    }

    public function sendFundsTransferredEmail(
        Member $sender,
        Member $recipient,
        Money $amount,
        DateTime $occurredOn
    ): void {
        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo($sender->emailAddress())
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

    public function sendDepositReceivedEmail(
        Member $sender,
        Member $recipient,
        Money $amount,
        DateTime $occurredOn
    ): void {
        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo($recipient->emailAddress())
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