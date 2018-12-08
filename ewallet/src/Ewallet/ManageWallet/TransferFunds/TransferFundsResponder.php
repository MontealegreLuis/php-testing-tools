<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\TransferFunds;

use Application\Actions\ActionResponder;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\UnknownMember;

/**
 * Represents the action executed by a member, it is usually implemented as a controller or a console command.
 * It is meant to provide feedback to a member that wants to transfer funds
 */
interface TransferFundsResponder extends ActionResponder
{
    public function respondToTransferCompleted(TransferFundsSummary $summary): void;

    public function respondToUnknownMember(UnknownMember $exception): void;

    public function respondToInsufficientFunds(InsufficientFunds $exception): void;
}
