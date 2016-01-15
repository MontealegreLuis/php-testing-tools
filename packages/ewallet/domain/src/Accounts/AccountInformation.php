<?php
/**
 * PHP version 5.6
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
     * @param $balance
     */
    public function __construct(Money$balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return Money
     */
    public function balance()
    {
        return $this->balance;
    }
}
