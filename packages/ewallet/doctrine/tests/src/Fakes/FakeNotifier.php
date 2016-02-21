<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Fakes;

use Ewallet\Wallet\TransferFundsNotifier;
use Ewallet\Wallet\TransferFundsResult;
use RuntimeException;

class FakeNotifier implements TransferFundsNotifier
{
    public function transferCompleted(TransferFundsResult $result)
    {
        throw new RuntimeException("Transfer failed.");
    }
}
