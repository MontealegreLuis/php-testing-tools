<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;
use Money\Money;

/**
 * Information needed to make a transfer
 */
class TransferFundsInformation
{
    /** @var MemberId */
    private $senderId;

    /** @var MemberId */
    private $recipientId;

    /** @var Money */
    private $amount;

    private function __construct(array $validInput)
    {
        $this->senderId = MemberId::withIdentity($validInput['senderId']);
        $this->recipientId = MemberId::withIdentity($validInput['recipientId']);
        $this->amount = Money::MXN((int) ($validInput['amount'] * 100));
    }

    public static function from(array $validInput): TransferFundsInformation
    {
        return new self($validInput);
    }

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
}
