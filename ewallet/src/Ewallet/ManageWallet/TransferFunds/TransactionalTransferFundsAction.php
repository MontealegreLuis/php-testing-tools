<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Application\Services\ProvidesTransactionalOperations;

final class TransactionalTransferFundsAction extends TransferFundsAction
{
    use ProvidesTransactionalOperations;

    /**
     * Execute the transfer in a transaction
     */
    public function transfer(TransferFundsInput $input): TransferFundsSummary
    {
        /** @var TransferFundsSummary $summary */
        $summary = $this->execute(fn(): TransferFundsSummary => parent::transfer($input));
        return $summary;
    }
}
