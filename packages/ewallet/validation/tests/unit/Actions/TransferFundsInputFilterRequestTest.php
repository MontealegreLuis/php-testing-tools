<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Actions;

use Ewallet\ContractTests\TransferFundsRequestTest;
use Ewallet\DataBuilders\A;
use Ewallet\Doctrine2\Accounts\MembersRepository;
use Ewallet\Zf2\InputFilter\Filters\TransferFundsFilter;
use Ewallet\Zf2\InputFilter\TransferFundsInputFilterRequest;
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
