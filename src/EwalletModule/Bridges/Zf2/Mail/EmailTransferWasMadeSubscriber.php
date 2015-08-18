<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\Mail;

use Ewallet\Accounts\Members;
use Ewallet\Accounts\TransferWasMade;
use EwalletModule\Controllers\TemplateEngine;
use Hexagonal\DomainEvents\Event;
use Hexagonal\DomainEvents\EventSubscriber;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class EmailTransferWasMadeSubscriber implements EventSubscriber
{
    /** @var Members */
    private $members;

    /** @var TemplateEngine */
    private $template;

    /** @var TransportInterface */
    private $mailTransport;

    /**
     * @param Members $members
     * @param TemplateEngine $template
     * @param TransportInterface $mailTransport
     */
    public function __construct(
        Members $members,
        TemplateEngine $template,
        TransportInterface $mailTransport
    ) {
        $this->members = $members;
        $this->template = $template;
        $this->mailTransport = $mailTransport;
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function isSubscribedTo(Event $event)
    {
        return TransferWasMade::class === get_class($event);
    }

    /**
     * @param Event $event
     * @return boolean
     */
    public function handle(Event $event)
    {
        $fromMember = $this->members->with($event->fromMemberId());
        $toMember = $this->members->with($event->toMemberId());

        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo((string) $fromMember->information()->email())
            ->setSubject('Funds transfer completed')
            ->setBody($this->template->render('email/transfer.html', [
                'fromMember' => $fromMember->information(),
                'toMember' => $toMember->information(),
                'amount' => $event->amount(),
                'occurredOn' => $event->occurredOn(),
            ]))
        ;
        $this->mailTransport->send($message);

        $message = new Message();
        $message
            ->setFrom('hello@ewallet.com')
            ->setTo((string) $toMember->information()->email())
            ->setSubject('You have received a deposit')
            ->setBody($this->template->render('email/deposit.html', [
                'fromMember' => $fromMember->information(),
                'toMember' => $toMember->information(),
                'amount' => $event->amount(),
                'occurredOn' => $event->occurredOn(),
            ]))
        ;

        $this->mailTransport->send($message);
    }
}
