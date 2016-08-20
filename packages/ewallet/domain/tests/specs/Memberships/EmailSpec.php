<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace specs\Ewallet\Memberships;

use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;

class EmailSpec extends ObjectBehavior
{
    function it_cannot_have_an_invalid_address()
    {
        $this->beConstructedWith('invalid email address');
        $this
            ->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation()
        ;
    }

    function it_has_access_to_its_address()
    {
        $this->beConstructedWith('montealegreluis@gmail.com');
        $this->address()->shouldBe('montealegreluis@gmail.com');
    }
}
