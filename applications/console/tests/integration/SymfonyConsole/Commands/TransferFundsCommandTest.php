<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Accounts\Member;
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Actions\TransferFundsAction;
use Ewallet\Responders\TransferFundsConsoleResponder;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Zf2\InputFilter\{
    Filters\TransferFundsFilter, TransferFundsInputFilterRequest
};
use Ewallet\Presenters\MemberFormatter;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\{
    Helper\QuestionHelper,
    Input\ArrayInput,
    Input\InputInterface,
    Output\BufferedOutput,
    Output\OutputInterface,
    Question\Question
};
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

    public function setUp()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.tests.php');
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
    }

    /** @test */
    function it_transfers_funds_between_members()
    {
        $fixture = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixture->load();
        $useCase = new TransferFunds(
            $members = $this->entityManager->getRepository(Member::class)
        );
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $question =  new class() extends QuestionHelper {
            public function ask(
                InputInterface $input,
                OutputInterface $output,
                Question $question
            ) {
                if ($question->getQuestion() === 'Transfer to ID: ') return 'LMN';
                return 5;
            }
        };
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
