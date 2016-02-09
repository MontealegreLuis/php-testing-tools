<?php
/**
 * PHP version 5.6
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
    public function occurredOn()
    {
        return $this->occurredOn;
    }

    /**
     * @return MemberId
     */
    public function fromMemberId()
    {
        return $this->fromMemberId;
    }

    /**
     * @return Money
     */
    public function amount()
    {
        return $this->amount;
    }

    /**
     * @return MemberId
     */
    public function toMemberId()
    {
        return $this->toMemberId;
    }
}
