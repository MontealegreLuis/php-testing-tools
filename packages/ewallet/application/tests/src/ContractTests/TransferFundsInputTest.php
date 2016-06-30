<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ContractTests;

use Ewallet\{Wallet\TransferFundsInput, DataBuilders\A};
use PHPUnit_Framework_TestCase as TestCase;

abstract class TransferFundsInputTest extends TestCase
{
    const VALID_ID = 'abc';
    const INVALID_ID = 'not a valid member ID';
    const VALID_AMOUNT = 12000;

    /** @var TransferFundsInput */
    protected $input;

    /**
     * This method should assign an implementation of the TransferFundsRequest
     * interface to the variable $request
     */
    abstract function inputInstance();

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
    function it_passes_validation_with_valid_member_id()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertTrue($this->input->isValid(), 'Member ID should be valid');
    }

    /** @test */
    function it_does_not_pass_validation_with_an_empty_member_id_to_transfer_to()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => '',
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->input->isValid(), 'An empty member ID should be invalid');
        $this->assertArrayHasKey('recipientId',$this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['recipientId']);
    }

    /** @test */
    function it_does_not_pass_validation_if_member_id_is_not_in_white_list()
    {
        $this->input->populate([
            'senderId' => self::VALID_ID,
            'recipientId' => self::INVALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->input->isValid(), 'Member ID should be invalid');
        $this->assertArrayHasKey('recipientId', $this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['recipientId']);
    }

    /** @test */
    function it_does_not_pass_validation_if_the_member_making_the_transfer_is_not_specified()
    {
        $this->input->populate([
            'senderId' => '',
            'recipientId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->input->isValid(), 'Member ID should be invalid');
        $this->assertArrayHasKey('senderId', $this->input->errorMessages());
        $this->assertInternalType('array', $this->input->errorMessages()['senderId']);
    }
}
