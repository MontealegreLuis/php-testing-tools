<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Money\Money;

/**
 * This class enables access to a member's account balance
 */
class AccountInformation
{
    /** @var Money */
    private $balance;

    /**
     * @param Money $balance
     */
    public function __construct(Money $balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return Money
     */
    public function balance(): Money
    {
        return $this->balance;
    }
}
