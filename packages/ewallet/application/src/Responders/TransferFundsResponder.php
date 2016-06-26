<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders;

use Ewallet\{Accounts\MemberId, Wallet\TransferFundsSummary};

interface TransferFundsResponder
{
    /**
     * @param TransferFundsSummary $summary
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary);

    /**
     * @param array $messages
     * @param array $values
     */
    public function respondToInvalidTransferInput(array $messages, array $values);

    /**
     * @param MemberId $fromMemberId
     */
    public function respondToEnterTransferInformation(MemberId $fromMemberId);
}
