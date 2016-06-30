<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\InputFilter\Filters;

use Ewallet\Accounts\Member;
use Zend\InputFilter\{Input, InputFilter};
use Zend\Validator\{GreaterThan, InArray, NotEmpty};

class TransferFundsFilter extends InputFilter
{
    /**
     * Configure validations for fields
     *
     * - senderId
     * - recipientId
     * - amount
     */
    public function __construct()
    {
        $this
            ->add($this->buildSenderIdInput())
            ->add($this->buildRecipientIdInput())
            ->add($this->buildAmountInput())
        ;
    }

    /**
     * @param array $membersAvailableForTransfer
     */
    public function configure(array $membersAvailableForTransfer)
    {
        $recipientId = $this->get('recipientId');

        $recipientId
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
    protected function buildSenderIdInput()
    {
        $senderId = new Input('senderId');
        $senderId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $senderId;
    }

    /**
     * @return Input
     */
    protected function buildRecipientIdInput()
    {
        $recipientId = new Input('recipientId');
        $recipientId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $recipientId;
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
