<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\ManageWallet\TransferFunds;

use PHPUnit\Framework\TestCase;

class TransferFundsInformationTest extends TestCase
{
    /** @test */
    function it_does_not_pass_validation_if_no_input_is_present()
    {
        $input = TransferFundsInformation::from([]);

        $isValid = $input->isValid();

        $this->assertFalse($isValid);
        $this->assertCount(3, $input->errors());
        $this->assertArrayHasKey('senderId', $input->errors());
        $this->assertArrayHasKey('recipientId', $input->errors());
        $this->assertArrayHasKey('amount', $input->errors());
    }

    /** @test */
    function it_does_not_pass_validation_if_ids_are_empty()
    {
        $input = TransferFundsInformation::from([
            'senderId' => '  ',
            'amount' => 1000,
        ]);

        $isValid = $input->isValid();

        $this->assertFalse($isValid);
        $this->assertCount(2, $input->errors());
        $this->assertArrayHasKey('senderId', $input->errors());
        $this->assertArrayHasKey('recipientId', $input->errors());
    }

    /** @test */
    function it_does_not_pass_validation_if_amount_is_not_greater_than_zero()
    {
        $input = TransferFundsInformation::from([
            'senderId' => 'ABC',
            'recipientId' => 'DEF',
            'amount' => 0,
        ]);

        $isValid = $input->isValid();

        $this->assertFalse($isValid);
        $this->assertCount(1, $input->errors());
        $this->assertArrayHasKey('amount', $input->errors());
    }
}
