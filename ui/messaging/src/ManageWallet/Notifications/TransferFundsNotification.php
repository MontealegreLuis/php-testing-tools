<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\Notifications;

use Application\Clock;
use Application\SystemClock;
use Carbon\CarbonImmutable;
use Ewallet\Memberships\MemberId;
use Money\Currency;
use Money\Money;

final class TransferFundsNotification
{
    private CarbonImmutable $occurredOn;

    private MemberId $senderId;

    private Money $amount;

    private MemberId $recipientId;

    public function __construct(string $senderId, int $amount, string $recipientId, Clock $clock = null)
    {
        $clock ??= new SystemClock();
        $this->occurredOn = $clock->now();
        $this->senderId = new MemberId($senderId);
        $this->amount = new Money($amount, new Currency('MXN'));
        $this->recipientId = new MemberId($recipientId);
    }

    public function occurredOn(): CarbonImmutable
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
