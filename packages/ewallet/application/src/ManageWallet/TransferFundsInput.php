<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

/**
 * Validate the raw input coming from members in order to know if it's safe to
 * use it, and notify them in case something is wrong so they can fix it.
 *
 * @see \Ewallet\ContractTests\TransferFundsInputTest in order to know the
 * details about the rules that the input must satisfy.
 */
interface TransferFundsInput
{
    public function populate(array $rawInput): void;

    /**
     * Is the raw input valid?
     */
    public function isValid(): bool;

    /**
     * @return string[]
     */
    public function errorMessages(): array;

    /**
     * The original raw input.
     *
     * This is usually shown back to the user through the UI, to provide feedback when validation
     * fails
     */
    public function values(): array;
}
