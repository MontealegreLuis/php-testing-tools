<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;

interface TransferFundsResponder
{
    /**
     * Provide a summary after a transfer has been successfully completed
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary): void;

    /**
     * Provide feedback to the user when its input is not valid
     */
    public function respondToInvalidTransferInput(array $messages, array $values): void;

    /**
     * Allow the user to input the data to make the transfer
     */
    public function respondToEnterTransferInformation(MemberId $senderId): void;
}
