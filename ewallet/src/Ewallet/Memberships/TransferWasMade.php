<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Memberships;

use Application\Clock;
use Application\DomainEvents\DomainEvent;
use Application\SystemClock;
use DateTimeInterface;
use Money\Money;

/**
 * This event is triggered every time a funds transfer is completed successfully
 */
final class TransferWasMade implements DomainEvent
{
    private DateTimeInterface $occurredOn;

    private MemberId $senderId;

    private Money $amount;

    private MemberId $recipientId;

    public function __construct(MemberId $senderId, Money $amount, MemberId $recipientId, Clock $clock = null)
    {
        $clock ??= new SystemClock();
        $this->occurredOn = $clock->now();
        $this->senderId = $senderId;
        $this->amount = $amount;
        $this->recipientId = $recipientId;
    }

    public function occurredOn(): DateTimeInterface
    {
        return $this->occurredOn;
    }

    public function senderId(): MemberId
    {
        return $this->senderId;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function recipientId(): MemberId
    {
        return $this->recipientId;
    }
}
