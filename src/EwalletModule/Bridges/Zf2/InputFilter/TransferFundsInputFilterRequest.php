<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2\InputFilter;

use EwalletModule\Bridges\EasyForms\MembersConfiguration;
use EwalletModule\Bridges\Zf2\InputFilter\Filters\TransferFundsFilter;
use EwalletModule\Actions\TransferFundsRequest;

class TransferFundsInputFilterRequest implements TransferFundsRequest
{
    /** @var TransferFundsFilter */
    private $filter;

    /** @var MembersConfiguration */
    private $configuration;

    /**
     * @param TransferFundsFilter $filter
     * @param MembersConfiguration $configuration
     */
    public function __construct(
        TransferFundsFilter $filter,
        MembersConfiguration $configuration
    ) {
        $this->filter = $filter;
        $this->configuration = $configuration;
    }

    /**
     * @param array $input
     */
    public function populate(array $input)
    {
        $fromMemberId = isset($input['fromMemberId']) ? $input['fromMemberId']: null;
        $this->filter->configure($this->configuration, $fromMemberId);
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
