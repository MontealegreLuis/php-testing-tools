<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Forms\Filters;

use EwalletModule\Forms\MembersConfiguration;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class TransferFundsFilter extends InputFilter
{
    /**
     * Configure validations for fields
     *
     * - toPartnerId
     * - amount
     */
    public function __construct()
    {
        $this->add($this->buildToMemberIdInput());
        $this->add($this->buildAmountInput());
    }

    /**
     * @param MembersConfiguration $configuration
     */
    public function configure(MembersConfiguration $configuration)
    {
        $toPartnerId = $this->get('toMemberId');

        $toPartnerId
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => $configuration->getMembersWhitelist()
            ]))
        ;
    }

    /**
     * @return Input
     */
    protected function buildToMemberIdInput()
    {
        $toPartnerId = new Input('toMemberId');
        $toPartnerId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $toPartnerId;
    }

    /**
     * @return Input
     */
    protected function buildAmountInput()
    {
        $amount = new Input('amount');
        $amount
            ->getValidatorChain()
            ->attach(new NotEmpty(['type' => NotEmpty::INTEGER]))
            ->attach(new Digits())
        ;

        return $amount;
    }
}
