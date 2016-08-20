<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use EasyForms\{Elements\Hidden, Elements\Select, Form};
use EWallet\Memberships\MemberId;
use Ewallet\EasyForms\Elements\MoneyElement;

class TransferFundsForm extends Form
{
    /**
     * It contains the IDs of the members involved in the transaction
     * and the amount to be transferred between accounts.
     */
    public function __construct()
    {
        $this
            ->add(new Hidden('senderId'))
            ->add(new Select('recipientId'))
            ->add(new MoneyElement('amount'))
        ;
    }

    /**
     * @param MembersConfiguration $configuration
     * @param MemberId $memberId
     */
    public function configure(
        MembersConfiguration $configuration,
        MemberId $memberId
    ) {
        $this->get('senderId')->setValue($memberId);
        $this
            ->get('recipientId')
            ->setChoices($configuration->getMembersChoicesExcluding($memberId))
        ;
    }
}
