<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace ContractTests\Ewallet\Memberships;

use DataBuilders\A;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\Members;
use Ewallet\Memberships\UnknownMember;
use Money\Money;
use PHPUnit\Constraints\ProvidesMoneyConstraints;
use PHPUnit\Framework\TestCase;

abstract class MembersTest extends TestCase
{
    use ProvidesMoneyConstraints;

    abstract protected function membersInstance(): Members;

    /** @test */
    function it_finds_a_registered_member()
    {
        $registeredMemberId = $this->registeredMember->id();

        $member = $this->members->with($registeredMemberId);

        $this->assertTrue(
            $member->equals($this->registeredMember),
            'Registered member with ID "abcd" should be found'
        );
    }

    /** @test */
    function it_fails_to_find_an_unknown_member()
    {
        $this->expectException(UnknownMember::class);

        $this->members->with(new MemberId('unknown member id'));
    }

    /** @test */
    function it_updates_the_information_of_a_registered_member()
    {
        $fivePesos = 500;
        $recipient = A::member()->build();
        $sender = $this->members->with($this->sender->id());

        $sender->transfer(Money::MXN($fivePesos), $recipient);
        $this->members->save($sender);

        $this->assertBalanceAmounts(
            $fivePesos,
            $this->members->with($sender->id()),
            "Current member balance should be $fivePesos"
        );
    }

    /** @before */
    function generateFixtures(): void
    {
        $this->members = $this->membersInstance();
        $this->registeredMember = A::member()->build();
        $this->sender = A::member()->withBalance(1000)->build();

        $this->members->save($this->sender);
        $this->members->save($this->registeredMember);
        $this->members->save(A::member()->build());
    }

    /** @var Members */
    protected $members;

    /** @var \Ewallet\Memberships\Member */
    protected $registeredMember;

    /** @var \Ewallet\Memberships\Member A member with 1000 cents */
    private $sender;
}
