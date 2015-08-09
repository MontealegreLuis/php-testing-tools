<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2InputFilter;

use EwalletModule\Controllers\FilteredRequest;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Bridges\Zf2InputFilter\Filters\TransferFundsFilter;

class TransferFundsInputFilterRequest implements FilteredRequest
{
    /** @var TransferFundsFilter */
    private $filter;

    /**
     * @param TransferFundsFilter $filter
     * @param MembersConfiguration $configuration
     * @param array $input
     */
    public function __construct(
        TransferFundsFilter $filter,
        MembersConfiguration $configuration,
        array $input
    ) {
        $this->filter = $filter;
        $fromMemberId = isset($input['fromMemberId']) ? $input['fromMemberId']: null;
        $this->filter->configure($configuration, $fromMemberId);
        $this->filter->setData($input);
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        return $this->filter->isValid();
    }

    /**
     * @return array
     */
    public function errorMessages()
    {
        return $this->filter->getMessages();
    }

    /**
     * @return array
     */
    public function values()
    {
        return $this->filter->getValues();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function value($key)
    {
        return $this->filter->getValue($key);
    }
}
