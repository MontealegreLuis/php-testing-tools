<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFundsResponse;

interface TransferFundsResponder
{
    /**
     * @param TransferFundsResponse $result
     */
    public function transferCompletedResponse(TransferFundsResponse $result);

    /**
     * @param array $messages
     * @param array $values
     * @param string $fromMemberId
     */
    public function invalidTransferInputResponse(
        array $messages,
        array $values,
        $fromMemberId
    );

    /**
     * @param Identifier $fromMemberId
     */
    public function transferFundsFormResponse(Identifier $fromMemberId);
}
