<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Bridges\Hexagonal\Wallet;

use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsRequest;
use Hexagonal\Application\Services\ProvidesTransactionalOperations;

class TransferFundsTransactionally extends TransferFunds
{
    use ProvidesTransactionalOperations;

    /**
     * @param TransferFundsRequest $request
     */
    public function transfer(TransferFundsRequest $request)
    {
        $this->session->executeAtomically(function () use ($request) {
            parent::transfer($request);
        });
    }
}
