<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ContractTests\Memberships;

use Ewallet\Memberships\{MemberId, Members};
use Ewallet\DataBuilders\A;
use Ewallet\PHPUnit\Constraints\ProvidesMoneyConstraints;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;

abstract class MembersTest extends TestCase
{
    use ProvidesMoneyConstraints;

    /** @var string */
    private $senderId = 'wxyz';

    /** @var Members */
    protected $members;

    /** @var \Ewallet\Memberships\Member */
    protected $registeredMember;

    /**
     * @return Members
     */
    abstract protected function membersInstance(): Members;

    /** @before */
    function generateFixtures()
    {
        $this->members = $this->membersInstance();
        $this->registeredMember = A::member()->withId('abcd')->withBalance(3000)->build();

        $this->members->add(A::member()->withId($this->senderId)->withBalance(1000)->build());
        $this->members->add($this->registeredMember);
        $this->members->add(A::member()->withId('hijk')->build());
    }

    /** @test */
    function it_finds_a_registered_member()
    {
        $member = $this->members->with(MemberId::withIdentity('abcd'));

        $this->assertTrue(
            $member->equals($this->registeredMember),
            'Registered member with ID "abcd" should be found'
        );
    }

    /**
     * @test
     * @expectedException \Ewallet\Memberships\UnknownMember
     */
    function it_does_not_find_a_non_existing_member()
    {
        $this->members->with(MemberId::withIdentity('lmno'));
    }

    /** @test */
    function it_updates_the_information_of_a_registered_member()
    {
        $sender = $this->members->with(MemberId::withIdentity($this->senderId));
        $sender->transfer(Money::MXN(500), $this->registeredMember);

        $this->members->update($this->registeredMember);

        $this->assertBalanceAmounts(
            3500,
            $this->members->with($this->registeredMember->information()->id()),
            "Current member balance should be 3500"
        );
    }
}
