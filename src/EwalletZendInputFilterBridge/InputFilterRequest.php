<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletZendInputFilterBridge;

use EwalletModule\Controllers\FilteredRequest;
use Zend\InputFilter\InputFilter;

class InputFilterRequest implements FilteredRequest
{
    /** @var InputFilter */
    private $filter;

    /**
     * @param InputFilter $filter
     * @param array $input
     */
    public function __construct(InputFilter $filter, array $input)
    {
        $this->filter = $filter;
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
