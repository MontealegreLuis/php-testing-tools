<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\PHPUnit;

use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Exception;
use PHPUnit_Framework_AssertionFailedError as AssertionFailedError;
use PHPUnit_Framework_Test as Test;
use PHPUnit_Framework_TestListener as TestListener;
use PHPUnit_Framework_TestSuite as TestSuite;

class UpdateDatabaseSchemaListener implements TestListener
{
    use ProvidesDoctrineSetup;

    /** @var string */
    private $path;

    /**
     * @param string $path Path to the integration tests configuration for Doctrine
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function addError(Test $test, Exception $e, $time) {}

    public function addFailure(Test $test, AssertionFailedError $e, $time) {}

    public function addIncompleteTest(Test $test, Exception $e, $time) {}

    public function addRiskyTest(Test $test, Exception $e, $time) {}

    public function addSkippedTest(Test $test, Exception $e, $time) {}

    /**
     * Update the database schema before running the integration tests
     *
     * @inheritDoc
     */
    public function startTestSuite(TestSuite $suite)
    {
        if ($suite->getName() !== 'integration') {
            return;
        }

        $this->_updateDatabaseSchema(require $this->path);
    }

    public function endTestSuite(TestSuite $suite) {}

    public function startTest(Test $test) {}

    public function endTest(Test $test, $time) {}
}
