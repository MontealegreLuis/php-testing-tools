<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\Notifications;

use DateTime;
use Ewallet\Memberships\Member;
use Money\Money;

/**
 * It will send an email to both sender and recipient, with the summary of their
 * last account transaction
 */
interface TransferFundsEmailSender
{
    public function sendFundsTransferredEmail(
        Member $sender,
        Member $recipient,
        Money $amount,
        DateTime $occurredOn
    ): void;

    public function sendDepositReceivedEmail(
        Member $sender,
        Member $recipient,
        Money $amount,
        DateTime $occurredOn
    ): void;
}
