<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

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
