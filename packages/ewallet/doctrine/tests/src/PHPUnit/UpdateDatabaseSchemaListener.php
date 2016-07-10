<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\PHPUnit;

use Doctrine\ORM\Tools\SchemaTool;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Exception;
use PHPUnit_Framework_AssertionFailedError as AssertionFailedError;
use PHPUnit_Framework_Test as Test;
use PHPUnit_Framework_TestListener as TestListener;
use PHPUnit_Framework_TestSuite as TestSuite;

class UpdateDatabaseSchemaListener implements TestListener
{
    use ProvidesDoctrineSetup;

    /**
     * @var string Path to the integration tests configuration for Doctrine
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function addError(Test $test, Exception $e, $time) {}

    /**
     * @inheritDoc
     */
    public function addFailure(Test $test, AssertionFailedError $e, $time) {}

    /**
     * @inheritDoc
     */
    public function addIncompleteTest(Test $test, Exception $e, $time) {}

    /**
     * @inheritDoc
     */
    public function addRiskyTest(Test $test, Exception $e, $time) {}

    /**
     * @inheritDoc
     */
    public function addSkippedTest(Test $test, Exception $e, $time) {}

    /**
     * @inheritDoc
     */
    public function startTestSuite(TestSuite $suite)
    {
        if ($suite->getName() !== 'integration') {
            return;
        }

        $this->_setUpDoctrine(require $this->path);
        $tool = new SchemaTool($em = $this->_entityManager());
        $tool->updateSchema($em->getMetadataFactory()->getAllMetadata(), true);
    }

    /**
     * @inheritDoc
     */
    public function endTestSuite(TestSuite $suite) {}

    /**
     * @inheritDoc
     */
    public function startTest(Test $test) {}

    /**
     * @inheritDoc
     */
    public function endTest(Test $test, $time) {}
}
