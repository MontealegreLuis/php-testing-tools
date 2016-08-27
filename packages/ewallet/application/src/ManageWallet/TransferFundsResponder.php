<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\MemberId;

interface TransferFundsResponder
{
    /**
     * Provide a summary after a transfer has been successfully completed
     *
     * @param TransferFundsSummary $summary
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary);

    /**
     * Provide feedback to the user when its input is not valid
     *
     * @param array $messages
     * @param array $values
     */
    public function respondToInvalidTransferInput(array $messages, array $values);

    /**
     * Allow the user to input the data to make the transfer
     *
     * @param MemberId $senderId
     */
    public function respondToEnterTransferInformation(MemberId $senderId);
}
