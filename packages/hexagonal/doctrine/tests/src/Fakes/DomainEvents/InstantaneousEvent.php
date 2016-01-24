<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Fakes\DomainEvents;

use DateTime;
use Ewallet\Accounts\Identifier;
use Hexagonal\DomainEvents\Event;
use Money\Money;

class InstantaneousEvent implements Event
{
    /** @var DateTime */
    private $occurredOn;

    /** @var Identifier */
    private $memberId;

    /** @var Money */
    private $amount;

    /**
     * @param Identifier $memberId
     * @param Money $amount
     * @param DateTime $instant
     */
    public function __construct(
        Identifier $memberId,
        Money $amount,
        DateTime $instant
    ) {
        $this->memberId = $memberId;
        $this->amount = $amount;
        $this->occurredOn = $instant;
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
