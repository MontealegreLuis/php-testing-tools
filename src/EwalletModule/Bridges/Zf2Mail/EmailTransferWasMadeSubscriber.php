<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2Mail;

use Ewallet\Accounts\Members;
use Ewallet\Accounts\TransferWasMade;
use Hexagonal\DomainEvents\Event;
use Hexagonal\DomainEvents\EventSubscriber;
use Twig_Environment as Twig;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class EmailTransferWasMadeSubscriber implements EventSubscriber
{
    /** @var Members */
    private $members;

    /** @var Twig */
    private $view;

    /** @var TransportInterface */
    private $mailTransport;

    /**
     * @param Members $members
     * @param Twig $view
     * @param TransportInterface $mailTransport
     */
    public function __construct(
        Members $members,
        Twig $view,
        TransportInterface $mailTransport
    ) {
        $this->members = $members;
        $this->view = $view;
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
            ->setBody($this->view->render('email/transfer.html.twig', [
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
            ->setBody($this->view->render('email/deposit.html.twig', [
                'fromMember' => $fromMember->information(),
                'toMember' => $toMember->information(),
                'amount' => $event->amount(),
                'occurredOn' => $event->occurredOn(),
            ]))
        ;

        $this->mailTransport->send($message);
    }
}
