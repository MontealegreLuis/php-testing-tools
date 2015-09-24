<?php
/**
 * PHP version 5.6
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
            ->taskExec('node_modules/.bin/phantomjs')
            ->option('webdriver', 4444)
            ->option('webdriver-loglevel', 'WARNING')
            ->background()
            ->run()
        ;
        $this
            ->taskServer(8000)
            ->dir('src/EwalletApplication/Bridges/Slim/Resources/web')
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

    /**
     * @description Run Behat, phpspec, PHPUnit and Codeception tests
     */
    public function test()
    {
        $this->stopOnFail();
        $this
            ->taskExec('php bin/behat')
            ->run()
        ;

        $this
            ->taskPhpspec('bin/phpspec')
            ->run()
        ;

        $this
            ->taskPhpUnit('bin/phpunit')
            ->option('testdox')
            ->run()
        ;

        $this->acceptance();
    }

    /**
     * @description Run the application using PHP built-in server
     */
    public function run()
    {
        $this->stopOnFail();
        $this
            ->taskServer(8000)
            ->dir('src/EwalletApplication/Bridges/Slim/Resources/web')
            ->background()
            ->run()
        ;

        while (true);
    }

    /**
     * @description Run an ewallet console command
     * @param string $ewalletCommand
     * @param array $args
     */
    public function console($ewalletCommand, array $args)
    {
        $console = $this->taskExec(
            'src/EwalletApplication/Bridges/SymfonyConsole/Resources/bin/console_dev'
        );

        $console->arg($ewalletCommand);
        foreach ($args as $arg) {
            $console->arg($arg);
        }
        $console->run();
    }
}
