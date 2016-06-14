<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

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
    private $fromMemberId;

    /** @var Money */
    private $amount;

    /** @var MemberId */
    private $toMemberId;

    /**
     * @param MemberId $fromMemberId
     * @param Money $amount
     * @param MemberId $toMemberId
     */
    public function __construct(
        MemberId $fromMemberId, Money $amount, MemberId $toMemberId
    ) {
        $this->occurredOn = new DateTime('now');
        $this->fromMemberId = $fromMemberId;
        $this->amount = $amount;
        $this->toMemberId = $toMemberId;
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
    public function fromMemberId(): MemberId
    {
        return $this->fromMemberId;
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
    public function toMemberId(): MemberId
    {
        return $this->toMemberId;
    }
}
