<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use Application\Services\ProvidesTransactionalOperations;
use Ewallet\Memberships\InsufficientFunds;

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
        try {
            $this->execute(function () use ($input): void {
                parent::transfer($input);
            });
        } catch (InsufficientFunds $exception) {
            $this->responder()->respondToInsufficientFunds($exception);
        }
    }
}
