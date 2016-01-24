<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Fakes\Symfony\Console;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class FakeQuestionHelper extends QuestionHelper
{
    /**
     * Returns `LMN` when asking for the ID to transfer to, and 5 if being asked
     * for the amount to transfer
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Question $question
     *
     * @return string The answer
     */
    public function ask(
        InputInterface $input,
        OutputInterface $output,
        Question $question
    ) {
        if ($question->getQuestion() === 'Transfer to ID: ') {
            return 'LMN';
        }
        return 5;
    }

}
