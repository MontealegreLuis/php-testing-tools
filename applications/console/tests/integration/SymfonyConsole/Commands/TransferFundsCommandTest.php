<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Accounts\Member;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Actions\TransferFundsAction;
use Ewallet\Responders\TransferFundsConsoleResponder;
use Ewallet\Zf2\InputFilter\Filters\TransferFundsFilter;
use Ewallet\Zf2\InputFilter\TransferFundsInputFilterRequest;
use Ewallet\View\MemberFormatter;
use Ewallet\Fakes\Symfony\Console\FakeQuestionHelper;
use Mockery;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Ewallet\TestHelpers\ProvidesDoctrineSetup;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

    public function setUp()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');
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
            __DIR__ . '/../../../fixtures/members.yml',
            $this->entityManager
        );
        $useCase = new TransferFunds(
            $members = $this->entityManager->getRepository(Member::class)
        );
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $question =  new FakeQuestionHelper();
        $action = new TransferFundsAction(
            new TransferFundsConsoleResponder(
                $input,
                $output,
                $question,
                $members,
                new MemberFormatter()
            ),
            $useCase
        );
        $command = new TransferFundsCommand(
            $action,
            new TransferFundsInputFilterRequest(
                new TransferFundsFilter(),
                $members
            )
        );

        $statusCode = $command->run($input, $output);

        $this->assertEquals($success = 0, $statusCode);
        $this->assertRegexp(
            '/Transfer completed successfully!/',
            $output->fetch()
        );
    }
}
