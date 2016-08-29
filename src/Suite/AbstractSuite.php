<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Coordinator;

/**
 * Most basic common test suite functionality.
 *
 * @since [*next-version*]
 */
abstract class AbstractSuite extends Test\AbstractSource implements SuiteInterface
{
    protected $results = array();
    protected $coordinator;

    /**
     * Sets the coordinator to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Coordinator\CoordinatorInterface $coordinator The coordinator to set.
     *
     * @return AbstractSuite This instance.
     */
    protected function _setCoordinator(Coordinator\CoordinatorInterface $coordinator)
    {
        $this->coordinator = $coordinator;

        return $this;
    }

    /**
     * Retrieve the coordinator that is used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Coordinator\CoordinatorInterface The coordinator used by this instance.
     */
    protected function _getCoordinator()
    {
        return $this->coordinator;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getTests()
    {
        return $this->_getTests();
    }

    /**
     * Low-level multiple tests retrieval.
     *
     * @since [*next-version*]
     *
     * @return Test\TestInterface[]|\Traversable The tests in this suite.
     */
    protected function _getTests()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function addTest(Test\TestInterface $test)
    {
        $this->_getCoordinator()->beforeAddTest($test, $this);
        if ($test->getSuiteCode()) {
            throw new \InvalidArgumentException(sprintf('Could not add test "%3$s" to suite "%1$s": test already belongs to suite "%2$s"', $this->getCode(), $test->getSuiteCode(), $test->getKey()));
        }

        $this->_addTest($test);
        $this->_getCoordinator()->afterAddTest($test, $this);

        return $this;
    }

    /**
     * Low-level single test adding.
     *
     * @since [*next-version*]
     *
     * @param Test\TestInterface $test The test to add.
     *
     * @return AbstractSuite This instance.
     */
    protected function _addTest(Test\TestInterface $test)
    {
        $test->setSuiteCode($this->getCode());
        $this->_addItem($test);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function addTests($tests)
    {
        $this->_addTests($tests);

        return $this;
    }

    /**
     * Low-level multiple test adding.
     *
     * @since [*next-version*]
     *
     * @param Test\TestInterface[]|\Traversable $tests The tests to add.
     *
     * @return AbstractSuite This instance.
     */
    protected function _addTests($tests)
    {
        foreach ($tests as $_test) {
            $this->addTest($_test);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getCode()
    {
        return $this->_getCode();
    }

    /**
     * Low-level suite code retrieval.
     *
     * @since [*next-version*]
     *
     * @return string The code of this suite.
     */
    protected function _getCode()
    {
        return $this->code;
    }

    /**
     * Low-level suite code setting.
     *
     * @since [*next-version*]
     *
     * @param string $code The suite code to set.
     *
     * @return AbstractSuite This instance.
     */
    protected function _setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return AbstractSuite This instance.
     */
    public function runAll()
    {
        $this->_beforeRunAll();
        $this->_runAll();
        $this->_afterRunAll();

        return $this;
    }

    /**
     * Executes before this suite runs all tests in it.
     *
     * @since [*next-version*]
     *
     * @return AbstractSuite This instance.
     */
    protected function _beforeRunAll()
    {
        return $this;
    }

    /**
     * Executes after this suite runs all tests in it.
     *
     * @since [*next-version*]
     *
     * @return AbstractSuite This instance.
     */
    protected function _afterRunAll()
    {
        return $this;
    }

    /**
     * Low-level run all tests.
     *
     * @since [*next-version*]
     *
     * @return AbstractSuite This instance.
     */
    protected function _runAll()
    {
        foreach ($this->getTests() as $_test) {
            /* @var $_test Test\TestInterface */
            $this->_beforeRunTest($_test);
            $result = $this->_runTest($_test);
            $this->_afterRunTest($result);
            $this->_recordResult($result);
        }

        return $this;
    }

    /**
     * Executed before a test is run by this suite.
     *
     * @since [*next-version*]
     *
     * @param Test\TestInterface $test The test that is about to be run.
     *
     * @return AbstractSuite This instance.
     */
    protected function _beforeRunTest(Test\TestInterface $test)
    {
        return $this;
    }

    /**
     * Executed after a test is run by this suite.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface $result The test that is about to be run.
     *
     * @return AbstractSuite This instance.
     */
    protected function _afterRunTest(Test\ResultInterface $result)
    {
        return $this;
    }

    /**
     * Runs a single test.
     *
     * Should not run the test directly, but use a runner instance, and
     * provide the test case with an assertion maker instance.
     *
     * @since [*next-version*]
     *
     * @param Test\TestInterface $test The test to run.
     *
     * @return Test\ResultInterface The result of the test.
     */
    protected function _runTest(Test\TestInterface $test)
    {
        $runner = $this->_getRunner();

        return $runner->run($test);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getResult($code)
    {
        if (!isset($this->tests[$code])) {
            throw new \InvalidArgumentException(sprintf('Could not retrieve test result: suite "%1$s" does not contain a test with code "%2$s"', $this->getCode(), $code));
        }

        return isset($this->results[$code])
                ? $this->results[$code]
                : null;
    }

    /**
     * Records the given test result in this suite.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface $result The result to record.
     *
     * @return AbstractSuite This instance.
     */
    protected function _recordResult(Test\ResultInterface $result)
    {
        $this->_getCoordinator()->beforeRecordTestResult($result, $this);
        $this->results[$result->getKey()] = $result;
        $this->_getCoordinator()->afterRecordTestResult($result, $this);

        return $this;
    }
}
