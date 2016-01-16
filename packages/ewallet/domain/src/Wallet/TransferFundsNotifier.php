<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

interface TransferFundsNotifier
{
    /**
     * @param TransferFundsResult $result
     */
    public function transferCompleted(TransferFundsResult $result);
}
