<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Accounts;

use Ewallet\Bridges\Tests\A;
use Ewallet\Bridges\Tests\ProvidesMoneyConstraint;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

abstract class MembersTest extends TestCase
{
    use ProvidesMoneyConstraint;

    /** @var Members */
    protected $members;

    /** @var Member */
    protected $existingMember;

    /**
     * @return Members
     */
    abstract protected function membersInstance();

    /** @before */
    function generateFixtures()
    {
        $this->members = $this->membersInstance();
        $this->existingMember = A::member()->withId('abcd')->withBalance(3000)->build();

        $this->members->add(
            A::member()->withId('wxyz')->withBalance(1000)->build()
        );
        $this->members->add($this->existingMember);
        $this->members->add(A::member()->withId('hijk')->build());
    }

    /** @test */
    function it_should_find_a_registered_member()
    {
        $member = $this->members->with(Identifier::fromString('abcd'));

        $this->assertTrue(
            $member->equals($this->existingMember),
            'Registered member with ID "abcd" should be found'
        );
    }

    /** @test */
    function it_should_not_find_a_non_existing_member()
    {
        $member = $this->members->with(Identifier::fromString('lmno'));

        $this->assertNull($member, 'Non existing member found');
    }

    /** @test */
    function it_should_update_the_information_of_a_registered_member()
    {
        $member = $this->members->with(Identifier::fromString('wxyz'));
        $member->transfer(Money::MXN(500), $this->existingMember);
        $this->members->update($this->existingMember);

        $this->assertBalanceAmounts(
            3500,
            $this->existingMember->information()->accountBalance(),
            "Current member balance should be 3500"
        );
    }
}
