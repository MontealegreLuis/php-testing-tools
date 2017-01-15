<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
class RoboFile extends \Robo\Tasks
{
    /**
     * @description Run all the Codeception acceptance tests in PhantomJS
     */
    public function acceptance()
    {
        $this->stopOnFail();
        $this
            ->taskExec('phantomjs')
            ->option('webdriver', 4444)
            ->option('webdriver-loglevel', 'WARNING')
            ->background()
            ->run()
        ;
        sleep(3); // Allow PhantomJS to start
        $this
            ->taskServer(8000)
            ->dir('public')
            ->background()
            ->run()
        ;
        $this
            ->taskExec('php bin/codecept')
            ->arg('clean')
            ->run()
        ;
        $this
            ->taskCodecept('bin/codecept')
            ->suite('acceptance')
            ->option('steps')
            ->run()
        ;
    }
}
