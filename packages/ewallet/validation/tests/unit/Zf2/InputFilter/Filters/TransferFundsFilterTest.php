<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\InputFilter\Filters;

use Ewallet\Accounts\Identifier;
use Ewallet\DataBuilders\A;
use Ewallet\Doctrine2\Accounts\MembersRepository;
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

    /**
     * @param integer $validAmount
     * @test
     * @dataProvider validAmountsProvider
     */
    function it_should_pass_validation_with_a_valid_amount($validAmount)
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => $validAmount,
        ]);

        $this->assertTrue($filter->isValid(), 'Amount should be valid');
    }

    public function validAmountsProvider()
    {
        return [[1], [500], [12000.34], [1000000]];
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

    /**
     * @param integer $invalidAmount
     * @test
     * @dataProvider invalidAmountsProvider
     */
    function it_should_not_pass_validation_if_amount_is_negative_or_zero($invalidAmount)
    {
        $filter = new TransferFundsFilter();
        $filter->setData([
            'fromMemberId' => self::VALID_ID,
            'toMemberId' => self::VALID_ID,
            'amount' => $invalidAmount,
        ]);

        $this->assertFalse($filter->isValid(), 'Negative amounts should be invalid');
        $this->assertArrayHasKey(GreaterThan::NOT_GREATER, $filter->getMessages()['amount']);
    }

    public function invalidAmountsProvider()
    {
        return [[0], [-500], [-12000.34]];
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

        /*$configuration = Mockery::mock(MembersRepository::class);
        $configuration
            ->shouldReceive('excluding')
            ->once()
            ->with(Identifier::with(self::VALID_ID))
            ->andReturn([
                A::member()->withId('abc')->build(),
                A::member()->withId('xyz')->build()
            ])
        ;*/

        $filter->configure([
            A::member()->withId('abc')->build(),
            A::member()->withId('xyz')->build()
        ]);

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
