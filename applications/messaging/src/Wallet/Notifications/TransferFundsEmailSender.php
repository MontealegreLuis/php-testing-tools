<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet\Notifications;

use DateTime;
use Ewallet\Accounts\MemberInformation;
use Money\Money;

/**
 * It will send an email to both members, summarizing their last account
 * transaction
 */
interface TransferFundsEmailSender
{
    /**
     * @param MemberInformation $fromMember
     * @param MemberInformation $toMember
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendFundsTransferredEmail(
        MemberInformation $fromMember,
        MemberInformation $toMember,
        Money $amount,
        DateTime $occurredOn
    );

    /**
     * @param MemberInformation $fromMember
     * @param MemberInformation $toMember
     * @param Money $amount
     * @param DateTime $occurredOn
     */
    public function sendDepositReceivedEmail(
        MemberInformation $fromMember,
        MemberInformation $toMember,
        Money $amount,
        DateTime $occurredOn
    );
}
