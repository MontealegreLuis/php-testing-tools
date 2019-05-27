<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use Application\Clock;
use Application\DomainEvents\DomainEvent;
use DateTime;
use DateTimeInterface;
use Money\Money;

/**
 * This event is triggered every time a funds transfer is completed successfully
 */
final class TransferWasMade implements DomainEvent
{
    /** @var DateTimeInterface */
    private $occurredOn;

    /** @var MemberId */
    private $senderId;

    /** @var Money */
    private $amount;

    /** @var MemberId */
    private $recipientId;

    public function __construct(MemberId $senderId, Money $amount, MemberId $recipientId)
    {
        $this->occurredOn = Clock::now();
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
