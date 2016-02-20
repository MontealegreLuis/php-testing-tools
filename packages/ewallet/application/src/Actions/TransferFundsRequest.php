<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

/**
 * Validate the raw input coming from members in order to know if it's safe to
 * use it, and notify them in case something is wrong so they can fix it.
 *
 * @see \Ewallet\ContractTests\TransferFundsRequestTest in order to know the
 * details about the rules that the input must satisfy.
 */
interface TransferFundsRequest
{
    /**
     * @param array $rawInput
     */
    public function populate(array $rawInput);

    /**
     * @return boolean
     */
    public function isValid();

    /**
     * @return array
     */
    public function errorMessages();

    /**
     * @return array
     */
    public function values();
}
