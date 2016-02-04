<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\MemberId;
use Money\Money;

class TransferFundsInformation
{
    /** @var MemberId */
    private $fromMemberId;

    /** @var MemberId */
    private $toMemberId;

    /** @var Money */
    private $amount;

    /**
     * @param array $filteredInput
     */
    private function __construct(array $filteredInput)
    {
        $this->fromMemberId = MemberId::with($filteredInput['fromMemberId']);
        $this->toMemberId = MemberId::with($filteredInput['toMemberId']);
        $this->amount = Money::MXN((integer) ($filteredInput['amount'] * 100));
    }

    /**
     * @param array $filteredInput
     * @return TransferFundsInformation
     */
    public static function from(array $filteredInput)
    {
        return new self($filteredInput);
    }

    /**
     * @return MemberId
     */
    public function fromMemberId()
    {
        return $this->fromMemberId;
    }

    /**
     * @return MemberId
     */
    public function toMemberId()
    {
        return $this->toMemberId;
    }

    /**
     * @return Money
     */
    public function amount()
    {
        return $this->amount;
    }
}
