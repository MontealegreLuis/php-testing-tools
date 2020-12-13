<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders;

use Adapters\Laminas\Application\InputValidation\LaminasInputFilter;
use Adapters\Symfony\Ewallet\ManageWallet\TransferFunds\TransferFundsValues;

final class Values
{
    /** @param mixed[] $override */
    public static function transferFundsValues(array $override = []): TransferFundsValues
    {
        return new TransferFundsValues(new LaminasInputFilter(array_merge([
            'senderId' => Random::uuid(),
            'recipientId' => Random::uuid(),
            'amount' => Random::dollars(),
        ], $override)));
    }
}
