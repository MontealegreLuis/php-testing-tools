<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Hexagonal\Application\Services\ProvidesTransactionalOperations;

class TransferFundsTransactionally extends TransferFunds
{
    use ProvidesTransactionalOperations;

    /**
     * @param TransferFundsInformation $information
     */
    public function transfer(TransferFundsInformation $information)
    {
        $this->session->executeAtomically(function () use ($information) {
            parent::transfer($information);
        });
    }
}
