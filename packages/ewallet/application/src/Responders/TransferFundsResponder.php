<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFundsResult;

interface TransferFundsResponder
{
    /**
     * @param TransferFundsResult $result
     */
    public function respondToTransferCompleted(TransferFundsResult $result);

    /**
     * @param array $messages
     * @param array $values
     */
    public function respondToInvalidTransferInput(array $messages, array $values);

    /**
     * @param Identifier $fromMemberId
     */
    public function respondToEnterTransferInformation(Identifier $fromMemberId);
}
