<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Hexagonal\Application\Services\ProvidesTransactionalOperations;

class TransactionalTransferFundsAction extends TransferFundsAction
{
    use ProvidesTransactionalOperations;

    /**
     * Execute the transfer in a transaction
     *
     * @inheritdoc
     */
    public function transfer(TransferFundsInput $input): void
    {
        $this->execute(function () use ($input) {
            parent::transfer($input);
        });
    }
}
