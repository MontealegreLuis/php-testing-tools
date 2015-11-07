<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Actions\EventSubscribers;

use DateTime;
use Ewallet\Accounts\Identifier;
use Money\Money;

class TransferFundsNotification
{
    /** @var DateTime */
    private $occurredOn;

    /** @var Identifier */
    private $fromMemberId;

    /** @var Money */
    private $amount;

    /** @var Identifier */
    private $toMemberId;

    /**
     * @param string $fromMemberId
     * @param string $amount
     * @param string $toMemberId
     * @param string $occurredOn
     */
    public function __construct($fromMemberId, $amount, $toMemberId, $occurredOn)
    {
        $this->occurredOn = DateTime::createFromFormat('Y-m-d H:i:s', $occurredOn);
        $this->fromMemberId = Identifier::fromString($fromMemberId);
        $this->amount = Money::MXN($amount);
        $this->toMemberId = Identifier::fromString($toMemberId);
    }

    /**
     * @return DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }

    /**
     * @return Identifier
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
     * @return Identifier
     */
    public function toMemberId()
    {
        return $this->toMemberId;
    }
}
