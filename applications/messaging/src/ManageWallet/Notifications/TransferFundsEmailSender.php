<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\Notifications;

use DateTime;
use Ewallet\Memberships\MemberInformation;
use Money\Money;

/**
 * It will send an email to both members, summarizing their last account
 * transaction
 */
interface TransferFundsEmailSender
{
    /**
     * @param MemberInformation $sender
     * @param MemberInformation $recipient
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendFundsTransferredEmail(
        MemberInformation $sender,
        MemberInformation $recipient,
        Money $amount,
        DateTime $occurredOn
    );

    /**
     * @param MemberInformation $sender
     * @param MemberInformation $recipient
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendDepositReceivedEmail(
        MemberInformation $sender,
        MemberInformation $recipient,
        Money $amount,
        DateTime $occurredOn
    );
}
