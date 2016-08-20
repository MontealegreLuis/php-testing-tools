<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Memberships;

use DateTime;
use Hexagonal\DomainEvents\Event;
use Money\Money;

/**
 * This event is triggered every time a funds transfer is completed successfully
 */
class TransferWasMade implements Event
{
    /** @var DateTime */
    private $occurredOn;

    /** @var MemberId */
    private $senderId;

    /** @var Money */
    private $amount;

    /** @var MemberId */
    private $recipientId;

    /**
     * @param MemberId $senderId
     * @param Money $amount
     * @param MemberId $recipientId
     */
    public function __construct(
        MemberId $senderId, Money $amount, MemberId $recipientId
    ) {
        $this->occurredOn = new DateTime('now');
        $this->senderId = $senderId;
        $this->amount = $amount;
        $this->recipientId = $recipientId;
    }

    /**
     * @return DateTime
     */
    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }

    /**
     * @return MemberId
     */
    public function senderId(): MemberId
    {
        return $this->senderId;
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @return MemberId
     */
    public function recipientId(): MemberId
    {
        return $this->recipientId;
    }
}
