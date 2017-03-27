<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\InputFilter\Filters;

use Ewallet\Memberships\Member;
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

    public function configure(array $validRecipients): void
    {
        $recipientId = $this->get('recipientId');

        $recipientId
            ->getValidatorChain()
            ->attach(new InArray([
                'haystack' => array_map(function (Member $member) {
                    return $member->information()->id();
                }, $validRecipients)
            ]))
        ;
    }

    private function buildSenderIdInput(): Input
    {
        $senderId = new Input('senderId');
        $senderId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $senderId;
    }

    private function buildRecipientIdInput(): Input
    {
        $recipientId = new Input('recipientId');
        $recipientId
            ->getValidatorChain()
            ->attach(new NotEmpty())
        ;

        return $recipientId;
    }

    private function buildAmountInput(): Input
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
