<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ContractTests;

use Ewallet\{Actions\TransferFundsRequest, DataBuilders\A};
use PHPUnit_Framework_TestCase as TestCase;

abstract class TransferFundsRequestTest extends TestCase
{
    const VALID_ID = 'abc';
    const INVALID_ID = 'not a valid member ID';
    const VALID_AMOUNT = 12000;

    /**
     * @var TransferFundsRequest
     */
    protected $request;

    /**
     * This method should assign an implementation of the TransferFundsRequest
     * interface to the variable $request
     */
    abstract function requestInstance();

    /**
     * @param int $validAmount
     * @test
     * @dataProvider validAmountsProvider
     */
    function it_should_pass_validation_with_a_valid_amount(int $validAmount)
    {
        $this->request->populate([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => $validAmount,
        ]);

        $this->assertTrue($this->request->isValid(), 'Amount should be valid');
    }

    public function validAmountsProvider()
    {
        return [[1], [500], [12000.34], [1000000]];
    }

    /** @test */
    function it_should_not_pass_validation_if_amount_is_empty()
    {
        $this->request->populate([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => '',
        ]);

        $this->assertFalse($this->request->isValid(), 'Amount should be invalid');
        $this->assertArrayHasKey('amount', $this->request->errorMessages());
        $this->assertInternalType('array', $this->request->errorMessages()['amount']);
    }

    /**
     * @param int $invalidAmount
     * @test
     * @dataProvider invalidAmountsProvider
     */
    function it_should_not_pass_validation_if_amount_is_negative_or_zero(
        int $invalidAmount
    ) {
        $this->request->populate([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => $invalidAmount,
        ]);

        $this->assertFalse($this->request->isValid(), 'Negative amounts should be invalid');
        $this->assertArrayHasKey('amount', $this->request->errorMessages());
        $this->assertInternalType('array', $this->request->errorMessages()['amount']);
    }

    public function invalidAmountsProvider()
    {
        return [[0], [-500], [-12000.34]];
    }

    /** @test */
    function it_should_pass_validation_with_valid_member_id()
    {
        $this->request->populate([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertTrue($this->request->isValid(), 'Member ID should be valid');
    }

    /** @test */
    function it_should_not_pass_validation_with_an_empty_member_id_to_transfer_to()
    {
        $this->request->populate([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => '',
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->request->isValid(), 'An empty member ID should be invalid');
        $this->assertArrayHasKey('toMemberId',$this->request->errorMessages());
        $this->assertInternalType('array', $this->request->errorMessages()['toMemberId']);
    }

    /** @test */
    function it_should_not_pass_validation_if_member_id_is_not_in_white_list()
    {
        $this->request->populate([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::INVALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->request->isValid(), 'Member ID should be invalid');
        $this->assertArrayHasKey('toMemberId', $this->request->errorMessages());
        $this->assertInternalType('array', $this->request->errorMessages()['toMemberId']);
    }

    /** @test */
    function it_should_not_pass_validation_if_the_member_making_the_transfer_is_not_specified()
    {
        $this->request->populate([
            'fromMemberId' => '',
            'toMemberId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($this->request->isValid(), 'Member ID should be invalid');
        $this->assertArrayHasKey('fromMemberId', $this->request->errorMessages());
        $this->assertInternalType('array', $this->request->errorMessages()['fromMemberId']);
    }
}
