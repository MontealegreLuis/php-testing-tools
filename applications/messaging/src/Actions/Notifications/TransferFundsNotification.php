<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions\Notifications;

use DateTime;
use Ewallet\Accounts\MemberId;
use Money\Money;

class TransferFundsNotification
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
     * @param string $fromMemberId
     * @param string $amount
     * @param string $toMemberId
     * @param string $occurredOn
     */
    public function __construct($fromMemberId, $amount, $toMemberId, $occurredOn)
    {
        $this->occurredOn = DateTime::createFromFormat('Y-m-d H:i:s', $occurredOn);
        $this->fromMemberId = MemberId::with($fromMemberId);
        $this->amount = Money::MXN($amount);
        $this->toMemberId = MemberId::with($toMemberId);
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
