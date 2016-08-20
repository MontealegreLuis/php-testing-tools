<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;
use Money\Money;

class TransferFundsInformation
{
    /** @var MemberId */
    private $senderId;

    /** @var MemberId */
    private $recipientId;

    /** @var Money */
    private $amount;

    /**
     * @param array $validInput
     */
    private function __construct(array $validInput)
    {
        $this->senderId = MemberId::withIdentity($validInput['senderId']);
        $this->recipientId = MemberId::withIdentity($validInput['recipientId']);
        $this->amount = Money::MXN((int) ($validInput['amount'] * 100));
    }

    /**
     * @param array $validInput
     * @return TransferFundsInformation
     */
    public static function from(array $validInput): TransferFundsInformation
    {
        return new self($validInput);
    }

    /**
     * @return MemberId
     */
    public function senderId(): MemberId
    {
        return $this->senderId;
    }

    /**
     * @return MemberId
     */
    public function recipientId(): MemberId
    {
        return $this->recipientId;
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }
}
