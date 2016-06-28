<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Wallet;

use Ewallet\Accounts\MembersRepository;
use Ewallet\ContractTests\TransferFundsInputTest;
use Ewallet\DataBuilders\A;
use Ewallet\Zf2\InputFilter\{
    Filters\TransferFundsFilter,
    TransferFundsInputFilter
};
use Mockery;

class TransferFundsInputFilterTest extends TransferFundsInputTest
{
    /**
     * @before
     */
    function inputInstance()
    {
        $members = Mockery::mock(MembersRepository::class);
        $members
            ->shouldReceive('excluding')
            ->andReturn([
                A::member()->withId('abc')->build(),
                A::member()->withId('xyz')->build()
            ])
        ;
        $this->input = new TransferFundsInputFilter(
            new TransferFundsFilter(),
            $members
        );
    }
}
