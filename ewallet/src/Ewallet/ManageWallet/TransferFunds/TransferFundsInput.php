<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Ewallet\Memberships\MemberId;
use Money\Currency;
use Money\Money;

/**
 * Information needed to make a transfer
 */
final class TransferFundsInput
{
    private MemberId $senderId;

    private MemberId $recipientId;

    private Money $amount;

    public function senderId(): MemberId
    {
        return $this->senderId;
    }

    public function recipientId(): MemberId
    {
        return $this->recipientId;
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    /** @param mixed[] $input */
    public function __construct(array $input)
    {
        $this->senderId = new MemberId($input['senderId']);
        $this->recipientId = new MemberId($input['recipientId']);
        $this->amount = new Money($input['amount'] * 100, new Currency('MXN'));
    }
}
