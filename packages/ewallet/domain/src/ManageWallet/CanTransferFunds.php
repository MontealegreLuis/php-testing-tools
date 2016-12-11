<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

/**
 * Represents the action executed by a user, it is usually implemented as a
 * controller or a console command. It is meant to provide feedback to the user
 * when a transfer is completed
 */
interface CanTransferFunds
{
    public function transferCompleted(TransferFundsSummary $summary);
}
