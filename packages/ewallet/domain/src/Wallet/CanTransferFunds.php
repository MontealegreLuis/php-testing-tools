<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

interface CanTransferFunds
{
    /**
     * @param TransferFundsSummary $summary
     */
    public function transferCompleted(TransferFundsSummary $summary);
}
