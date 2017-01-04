<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ContractTests;

use Ewallet\ManageWallet\TransferFundsInput;
use PHPUnit_Framework_TestCase as TestCase;

abstract class TransferFundsInputTest extends TestCase
{
    /**
     * @test
     * @dataProvider validAmountsProvider
     */
    function it_passes_validation_with_a_valid_amount(int $validAmount)
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::VALID_ID,
            'amount' => $validAmount,
        ]);

        $this->assertTrue($this->input->isValid(), 'Amount should be valid');
    }

    function validAmountsProvider()
    {
        return [[1], [500], [12000.34], [1000000]];
    }

    /** @test */
    function it_does_not_pass_validation_if_amount_is_empty()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::VALID_ID,
            'amount' => '',
        ]);

        $this->assertFalse($this->input->isValid(), 'Amount should be invalid');
        $this->assertArrayHasKey('amount', $this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['amount']);
    }

    /**
     * @test
     * @dataProvider invalidAmountsProvider
     */
    function it_does_not_pass_validation_if_amount_is_negative_or_zero(
        int $invalidAmount
    ) {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::VALID_ID,
            'amount' => $invalidAmount,
        ]);

        $this->assertFalse($this->input->isValid(), 'Negative amounts should be invalid');
        $this->assertArrayHasKey('amount', $this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['amount']);
    }

    function invalidAmountsProvider()
    {
        return [[0], [-500], [-12000.34]];
    }

    /** @test */
    function it_passes_validation_with_valid_sender_and_recipient_ids()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertTrue(
            $this->input->isValid(),
            'Both Sender and Recipient ID should be valid'
        );
    }

    /** @test */
    function it_does_not_pass_validation_with_an_empty_recipient_id()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => '',
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->input->isValid(), 'An empty recipient ID should be invalid');
        $this->assertArrayHasKey('recipientId',$this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['recipientId']);
    }

    /** @test */
    function it_does_not_pass_validation_if_recipient_id_is_not_in_white_list()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::INVALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->input->isValid(), 'Recipient ID should be invalid');
        $this->assertArrayHasKey('recipientId', $this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['recipientId']);
    }

    /** @test */
    function it_does_not_pass_validation_if_the_sender_id_is_not_specified()
    {
        $this->input->populate([
            'senderId' => '',
            'recipientId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->input->isValid(), 'Sender ID should be invalid');
        $this->assertArrayHasKey('senderId', $this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['senderId']);
    }

    /** @before */
    public function configureInput()
    {
        $this->input = $this->inputInstance();
    }

    abstract function inputInstance(): TransferFundsInput;

    /** @var TransferFundsInput */
    protected $input;

    const VALID_ID = 'abc';
    const INVALID_ID = 'not a valid member ID';
    const VALID_AMOUNT = 12000;
}
