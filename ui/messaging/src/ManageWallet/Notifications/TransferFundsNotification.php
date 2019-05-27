<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\Notifications;

use Application\Clock;
use DateTimeInterface;
use Ewallet\Memberships\MemberId;
use Money\Currency;
use Money\Money;

class TransferFundsNotification
{
    /** @var DateTimeInterface */
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
        $this->occurredOn = Clock::fromFormattedString($occurredOn);
        $this->senderId = new MemberId($senderId);
        $this->amount = new Money($amount, new Currency('MXN'));
        $this->recipientId = new MemberId($recipientId);
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
