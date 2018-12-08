<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\Notifications;

use DateTime;
use Ewallet\Memberships\MemberId;
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

    public function __construct(
        string $senderId,
        int $amount,
        string $recipientId,
        string $occurredOn
    ) {
        $this->occurredOn = DateTime::createFromFormat('Y-m-d H:i:s', $occurredOn);
        $this->senderId = MemberId::withIdentity($senderId);
        $this->amount = Money::MXN($amount);
        $this->recipientId = MemberId::withIdentity($recipientId);
    }

    public function occurredOn(): DateTime
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
