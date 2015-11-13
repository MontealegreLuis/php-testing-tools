<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\SymfonyConsole\Commands;

use Ewallet\Accounts\Member;
use Ewallet\Wallet\TransferFunds;
use EwalletModule\View\MemberFormatter;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use TestHelpers\Bridges\ProvidesDoctrineSetup;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

    public function setUp()
    {
        $this->_setUpDoctrine();
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
    }

    /** @test */
    function it_should_transfer_funds_between_members()
    {
        Fixtures::load(
            __DIR__ . '/../../../../../_data/fixtures/members.yml',
            $this->entityManager
        );
        $useCase = new TransferFunds(
            $this->entityManager->getRepository(Member::class)
        );
        $tester = new CommandTester(
            new TransferFundsCommand($useCase, new MemberFormatter())
        );

        $tester->execute([
            'fromMemberId' => 'LMN',
            'toMemberId' => 'ABC',
            'amount' => 5
        ]);

        $this->assertRegexp(
            '/Transfer completed successfully!/',
            $tester->getDisplay()
        );
    }
}
