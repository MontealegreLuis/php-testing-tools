<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\Zf2InputFilter\Filters;

use EwalletModule\Forms\MembersConfiguration;
use PHPUnit_Framework_TestCase as TestCase;
use Mockery;
use Zend\Validator\GreaterThan;
use Zend\Validator\InArray;
use Zend\Validator\NotEmpty;

class TransferFundsFilterTest extends TestCase
{
    const VALID_ID = 'a valid ID';
    const INVALID_ID = 'not a valid member ID';
    const VALID_AMOUNT = 12000;

    /** @test */
    function it_should_pass_validation_with_a_valid_amount()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertTrue($filter->isValid(), 'Amount should be valid');
    }

    /** @test */
    function it_should_pass_validation_with_a_float_number_amount()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => 12000.34
        ]);

        $this->assertTrue($filter->isValid(), 'Amounts with floating point numbers should be valid');
    }

    /** @test */
    function it_should_not_pass_validation_if_amount_is_empty()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => '',
        ]);

        $this->assertFalse($filter->isValid(), 'Amount should be invalid');
        $this->assertArrayHasKey(GreaterThan::NOT_GREATER, $filter->getMessages()['amount']);
    }

    /** @test */
    function it_should_not_pass_validation_if_amount_is_negative()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => -500,
        ]);

        $this->assertFalse($filter->isValid(), 'Negative amounts should be invalid');
        $this->assertArrayHasKey(GreaterThan::NOT_GREATER, $filter->getMessages()['amount']);
    }

    /** @test */
    function it_should_pass_validation_with_valid_member_id()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertTrue($filter->isValid(), 'Member ID should be valid');
    }

    /** @test */
    function it_should_not_pass_validation_with_an_empty_member_id_to_transfer_to()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => '',
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($filter->isValid(), 'An empty member ID should be invalid');
        $this->assertArrayHasKey(NotEmpty::IS_EMPTY, $filter->getMessages()['toMemberId']);
    }

    /** @test */
    function it_should_not_pass_validation_if_member_id_is_not_in_white_list()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::INVALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $configuration = Mockery::mock(MembersConfiguration::class);
        $configuration
            ->shouldReceive('getMembersWhiteList')
            ->once()
            ->with(self::VALID_ID)
            ->andReturn(['abc', 'xyz'])
        ;

        $filter->configure($configuration, self::VALID_ID);

        $this->assertFalse($filter->isValid(), 'Member ID should be invalid');
        $this->assertArrayHasKey(InArray::NOT_IN_ARRAY, $filter->getMessages()['toMemberId']);
    }

    /** @test */
    function it_should_not_pass_validation_if_the_member_making_the_transfer_is_not_specified()
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => '',
            'toMemberId' => self::VALID_ID,
            'amount' => self::VALID_AMOUNT,
        ]);

        $this->assertFalse($filter->isValid(), 'Member ID should be invalid');
        $this->assertArrayHasKey(NotEmpty::IS_EMPTY, $filter->getMessages()['fromMemberId']);
    }
}
