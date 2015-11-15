<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Monolog;

use Ewallet\Accounts\TransferWasMade;
use EwalletModule\View\MemberFormatter;
use Hexagonal\DomainEvents\Event;
use Hexagonal\DomainEvents\EventSubscriber;
use Monolog\Logger;

class LogTransferWasMadeSubscriber implements EventSubscriber
{
    /** @var Logger */
    private $logger;

    /** @var MemberFormatter */
    private $formatter;

    /**
     * @param Logger $logger
     * @param MemberFormatter $formatter
     */
    public function __construct(Logger $logger, MemberFormatter $formatter)
    {
        $this->logger = $logger;
        $this->formatter = $formatter;
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
        $this->logger->info(sprintf(
            'Member with ID "%s" transferred %s to member with ID "%s" on %s',
            $event->fromMemberId(),
            $this->formatter->formatMoney($event->amount()),
            $event->toMemberId(),
            $event->occurredOn()->format('Y-m-d H:i:s')
        ));
    }
}
