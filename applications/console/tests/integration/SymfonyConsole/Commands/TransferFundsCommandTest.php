<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Accounts\{Member, MemberFormatter};
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Ewallet\Wallet\{TransferFundsAction, TransferFundsConsoleResponder, TransferFunds};
use Ewallet\Zf2\InputFilter\{Filters\TransferFundsFilter, TransferFundsInputFilter};
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\{ArrayInput, InputInterface};
use Symfony\Component\Console\Output\{BufferedOutput, OutputInterface};
use Symfony\Component\Console\Question\Question;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

    public function setUp()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.tests.php');
        $fixture = new ThreeMembersWithSameBalanceFixture($this->_entityManager());
        $fixture->load();
    }

    /** @test */
    function it_transfers_funds_between_members()
    {
        $members = $this->_entityManager()->getRepository(Member::class);
        $useCase = new TransferFunds($members);
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
            new TransferFundsInputFilter(
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
