<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Suite;
use Dhii\SimpleTest\Coordinator;
use Dhii\SimpleTest\Runner;
use Dhii\SimpleTest\Test;

/**
 * Common base functionality for testers.
 *
 * @since [*next-version*]
 */
abstract class AbstractTester implements TesterInterface
{
    /**
     * Higher-level coordinator retrieval.
     *
     * @since [*next-version*]
     *
     * @return Coordinator\CoordinatorInterface
     */
    abstract protected function _getCoordinatorInstance();

    /**
     * Higher-level runner retrieval.
     *
     * @since [*next-version*]
     *
     * @return Runner\RunnerInterface The runner used by this instance.
     */
    abstract protected function _getRunnerInstance();

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function runAll()
    {
        $this->_beforeRunAll();
        $results = $this->_runAll();
        $results = $this->_prepareResults($results);
        $this->_afterRunAll($results);

        return $results;
    }

    /**
     * Low-level running of tests in this tester's suites.
     *
     * @since [*next-version*]
     *
     * @return Test\ResultInterface[]|\Traversable A list of test result lists, by suite code.
     */
    protected function _runAll()
    {
        $runner  = $this->_getRunnerInstance();
        $results = array();
        foreach ($this->_getSuites() as $_code => $_suite) {
            /* @var $_suite SuiteInterface */
            $this->_beforeRunSuite($_suite);
            $suiteResults = $runner->runAll($_suite);
            $this->_afterRunSuite($_suite);

            $results[$_suite->getCode()] = $suiteResults;
        }

        return $results;
    }

    /**
     * Prepares a result set from an array of results.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface[]|\Traversable $results A traversible list of result sets.
     *
     * @return Test\ResultSetInterface The list of result sets.
     */
    protected function _prepareResults($results)
    {
        return $this->_createResultSetIterator($results);
    }

    /**
     * Executes after tests in one of this tester's suites are executed.
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    protected function _beforeRunSuite(Suite\SuiteInterface $suite)
    {
        $this->_getCoordinatorInstance()->beforeRunSuite($suite, $this);

        return $this;
    }

    /**
     * Executes after tests in one of this tester's suites are executed.
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    protected function _afterRunSuite(Suite\SuiteInterface $suite)
    {
        $this->_getCoordinatorInstance()->afterRunSuite($suite, $this);

        return $this;
    }

    /**
     * Executes after tests in this tester's suites are executed.
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    protected function _afterRunAll(Test\ResultSetInterface $results)
    {
        $this->_getCoordinatorInstance()->afterRunAllSuites($results, $this);

        return $this;
    }

    /**
     * Executes before tests in this tester's suites are executed.
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    protected function _beforeRunAll()
    {
        $this->_getCoordinatorInstance()->beforeRunAllSuites($this, $this);

        return $this;
    }

    /**
     * Retrieves the suites of this tester.
     *
     * @since [*next-version*]
     *
     * @return Suite\SuiteInterface[]
     */
    abstract protected function _getSuites();
}
