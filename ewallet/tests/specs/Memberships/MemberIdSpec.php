<?php
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace specs\Ewallet\Memberships;

use Assert\InvalidArgumentException;
use Ewallet\Memberships\MemberId;
use PhpSpec\ObjectBehavior;

class MemberIdSpec extends ObjectBehavior
{
    function it_has_access_to_its_identity_value()
    {
        $this->beConstructedWith('abcd');
        $this->value()->shouldBe('abcd');
    }

    function its_value_cannot_be_empty()
    {
        $this->beConstructedWith('');
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_knows_when_it_is_equal_to_another_id()
    {
        $this->beConstructedWith('abcd');
        $this->equals(new MemberId('abcd'))->shouldBe(true);
    }

    function it_can_be_converted_to_string()
    {
        $this->beConstructedWith('abcd');
        $this->__toString()->shouldBe('abcd');
    }
}
