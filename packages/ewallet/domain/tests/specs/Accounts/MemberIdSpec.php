<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\Accounts;

use Assert\InvalidArgumentException;
use Ewallet\Accounts\MemberId;
use PhpSpec\ObjectBehavior;

class MemberIdSpec extends ObjectBehavior
{
    function it_can_be_created_from_a_string()
    {
        $this->beConstructedThrough('with', ['abcd']);
        $this->value()->shouldBe('abcd');
    }

    function it_cannot_be_created_from_an_empty_string()
    {
        $this->beConstructedThrough('with', ['']);
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_knows_when_it_is_equal_to_another_id()
    {
        $this->beConstructedThrough('with', ['abcd']);
        $this->equals(MemberId::with('abcd'))->shouldBe(true);
    }

    function it_can_be_converted_to_string()
    {
        $this->beConstructedThrough('with', ['abcd']);
        $this->__toString()->shouldBe('abcd');
    }
}
