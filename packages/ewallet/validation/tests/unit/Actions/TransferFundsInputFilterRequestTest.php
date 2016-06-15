<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

use Ewallet\Accounts\MembersRepository;
use Ewallet\ContractTests\TransferFundsRequestTest;
use Ewallet\DataBuilders\A;
use Ewallet\Zf2\InputFilter\{
    Filters\TransferFundsFilter,
    TransferFundsInputFilterRequest
};
use Mockery;

class TransferFundsInputFilterRequestTest extends TransferFundsRequestTest
{
    /**
     * @before
     */
    function requestInstance()
    {
        $members = Mockery::mock(MembersRepository::class);
        $members
            ->shouldReceive('excluding')
            ->andReturn([
                A::member()->withId('abc')->build(),
                A::member()->withId('xyz')->build()
            ])
        ;
        $this->request = new TransferFundsInputFilterRequest(
            new TransferFundsFilter(),
            $members
        );
    }
}
