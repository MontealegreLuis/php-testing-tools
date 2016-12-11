<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{TransferWasMade, MemberFormatter};
use Hexagonal\DomainEvents\{Event, EventSubscriber};
use Psr\Log\LoggerInterface;

class TransferWasMadeLogger implements EventSubscriber
{
    /** @var LoggerInterface */
    private $logger;

    /** @var MemberFormatter */
    private $formatter;

    public function __construct(
        LoggerInterface $logger,
        MemberFormatter $formatter
    ) {
        $this->logger = $logger;
        $this->formatter = $formatter;
    }

    public function isSubscribedTo(Event $event): bool
    {
        return TransferWasMade::class === get_class($event);
    }

    public function handle(Event $event): void
    {
        $this->logger->info(sprintf(
            'Member with ID "%s" transferred %s to member with ID "%s" on %s',
            $event->senderId(),
            $this->formatter->formatMoney($event->amount()),
            $event->recipientId(),
            $event->occurredOn()->format('Y-m-d H:i:s')
        ));
    }
}
