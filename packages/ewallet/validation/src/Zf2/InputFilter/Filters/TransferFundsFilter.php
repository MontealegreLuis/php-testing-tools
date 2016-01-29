<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\InputFilter\Filters;

use Ewallet\Accounts\Member;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
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
     * @param array $membersAvailableForTransfer
     */
    public function configure(array $membersAvailableForTransfer)
    {
        $toPartnerId = $this->get('toMemberId');

        $toPartnerId
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => array_map(function (Member $member) {
                    return $member->information()->id();
                }, $membersAvailableForTransfer)
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
            ->attach(new NotEmpty(['type' => NotEmpty::FLOAT]))
            ->attach(new GreaterThan(['min' => 0]))
        ;

        return $amount;
    }
}
