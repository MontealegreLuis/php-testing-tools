<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet\Notifications;

use DateTime;
use Ewallet\Accounts\MemberId;
use Money\Money;

class TransferFundsNotification
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
     * @param string $senderId
     * @param string $amount
     * @param string $recipientId
     * @param string $occurredOn
     */
    public function __construct(
        string $senderId,
        string $amount,
        string $recipientId,
        string $occurredOn
    ) {
        $this->occurredOn = DateTime::createFromFormat('Y-m-d H:i:s', $occurredOn);
        $this->senderId = MemberId::with($senderId);
        $this->amount = Money::MXN($amount);
        $this->recipientId = MemberId::with($recipientId);
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
