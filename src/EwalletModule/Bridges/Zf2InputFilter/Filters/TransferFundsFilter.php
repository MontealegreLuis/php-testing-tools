<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2InputFilter\Filters;

use EwalletModule\Forms\MembersConfiguration;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
use Zend\Validator\GreaterThan;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class TransferFundsFilter extends InputFilter
{
    /**
     * Configure validations for fields
     *
     * - fromMemberId
     * - toMemberId
     * - amount
     */
    public function __construct()
    {
        $this
            ->add($this->buildFromMemberIdInput())
            ->add($this->buildToMemberIdInput())
            ->add($this->buildAmountInput())
        ;
    }

    /**
     * @param MembersConfiguration $configuration
     * @param string $fromMemberId
     */
    public function configure(MembersConfiguration $configuration, $fromMemberId)
    {
        $toPartnerId = $this->get('toMemberId');

        $toPartnerId
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => $configuration->getMembersWhitelist($fromMemberId)
            ]))
        ;
    }

    /**
     * @return Input
     */
    protected function buildFromMemberIdInput()
    {
        $fromMemberId = new Input('fromMemberId');
        $fromMemberId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $fromMemberId;
    }

    /**
     * @return Input
     */
    protected function buildToMemberIdInput()
    {
        $toMemberId = new Input('toMemberId');
        $toMemberId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $toMemberId;
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
            ->attach(new GreaterThan(['min' => 0]))
            ->attach(new Digits())
        ;

        return $amount;
    }
}
