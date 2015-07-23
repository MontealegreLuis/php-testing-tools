<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Controllers;

use Ewallet\Accounts\Identifier;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsResult;
use EwalletModule\Forms\MembersConfiguration;
use EwalletModule\Forms\TransferFundsForm;
use Mockery;
use Money\Money;
use PHPUnit_Framework_TestCase as TestCase;
use Twig_Environment as Twig;

class TransferFundsControllerTest extends TestCase
{
    /** @test */
    function it_should_transfer_funds_form()
    {
    }
}
