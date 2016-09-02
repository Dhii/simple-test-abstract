<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Suite;
use Dhii\SimpleTest\Coordinator;
use Dhii\SimpleTest\Runner;
use Dhii\SimpleTest\Test;

/**
 * Common functionality for testers.
 *
 * @since [*next-version*]
 */
abstract class AbstractTester implements TesterInterface
{
    protected $suites;
    protected $coordinator;
    protected $runner;

    /**
     * Sets the coordinator to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Coordinator\CoordinatorInterface $coordinator The coordinator to set.
     *
     * @return AbstractTester This instance.
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
     * Retrieve the runner used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Runner\RunnerInterface The runner used by this instance.
     */
    protected function _getRunner()
    {
        return $this->runner;
    }

    /**
     * Set the runner to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Runner\RunnerInterface $runner The runner to be used by this instance.
     */
    protected function _setRunner(Runner\RunnerInterface $runner)
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    public function addSuite(Suite\SuiteInterface $suite)
    {
        $this->_getCoordinator()->beforeAddSuite($suite, $this);
        $this->suites[$suite->getCode()] = $suite;
        $this->_getCoordinator()->afterAddSuite($suite, $this);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function runAll()
    {
        $this->_beforeRunAll();
        $results = $this->_runAll();
        $results = $this->_createResultSetIterator($results);
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
        $runner  = $this->_getRunner();
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
     * Create a new iterator of test result sets.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface[]|\Traversable $results A traversible list of result sets.
     *
     * @return Test\ResultSetInterface The list of result sets.
     */
    abstract protected function _createResultSetIterator($results);

    /**
     * Executes after tests in one of this tester's suites are executed.
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    protected function _beforeRunSuite(Suite\SuiteInterface $suite)
    {
        $this->_getCoordinator()->beforeRunSuite($suite, $this);

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
        $this->_getCoordinator()->afterRunSuite($suite, $this);

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
        $this->_getCoordinator()->afterRunAllSuites($results, $this);

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
        $this->_getCoordinator()->beforeRunAllSuites($this, $this);

        return $this;
    }

    /**
     * @since [*next-version*]
     *
     * @return Suite\SuiteInterface[]
     */
    protected function _getSuites()
    {
        return $this->suites;
    }
}
