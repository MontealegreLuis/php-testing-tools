<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders;

use Ewallet\ManageWallet\TransferFunds\TransferFundsInput;

final class Input
{
    /** @param mixed[] $override */
    public static function transferFunds(array $override = []): TransferFundsInput
    {
        return new TransferFundsInput(array_merge([
            'senderId' => Random::uuid(),
            'recipientId' => Random::uuid(),
            'amount' => Random::dollars(),
        ], $override));
    }
}
