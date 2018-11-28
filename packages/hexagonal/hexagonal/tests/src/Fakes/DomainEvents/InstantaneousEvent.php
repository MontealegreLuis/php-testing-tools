<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Hexagonal\Fakes\DomainEvents;

use DateTime;
use Ewallet\Memberships\MemberId;
use Hexagonal\DomainEvents\Event;
use Money\Money;

class InstantaneousEvent implements Event
{
    /** @var DateTime */
    private $occurredOn;

    /** @var MemberId */
    private $memberId;

    /** @var Money */
    private $amount;

    public function __construct(
        MemberId $memberId,
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
    public function occurredOn(): DateTime
    {
        return $this->occurredOn;
    }
}
