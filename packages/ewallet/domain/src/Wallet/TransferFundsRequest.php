<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\Identifier;
use Money\Money;

class TransferFundsRequest
{
    /** @var Identifier */
    private $fromMemberId;

    /** @var Identifier */
    private $toMemberId;

    /** @var Money */
    private $amount;

    /**
     * @param array $filteredInput
     */
    private function __construct(array $filteredInput)
    {
        $this->fromMemberId = Identifier::with($filteredInput['fromMemberId']);
        $this->toMemberId = Identifier::with($filteredInput['toMemberId']);
        $this->amount = Money::MXN((integer) ($filteredInput['amount'] * 100));
    }

    /**
     * @param array $filteredInput
     * @return TransferFundsRequest
     */
    public static function from(array $filteredInput)
    {
        return new self($filteredInput);
    }

    /**
     * @return Identifier
     */
    public function fromMemberId()
    {
        return $this->fromMemberId;
    }

    /**
     * @return Identifier
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
