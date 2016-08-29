<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Suite;
use Dhii\SimpleTest\Coordinator;

/**
 * Common functionality for testers.
 *
 * @since [*next-version*]
 */
abstract class AbstractTester implements TesterInterface
{
    protected $suites;
    protected $coordinator;

    /**
     * Sets the coordinator to be used by this instance.
     *
     * @since [*next-version*]
     * @param Coordinator\CoordinatorInterface $coordinator The coordinator to set.
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
     * @return Coordinator\CoordinatorInterface The coordinator used by this instance.
     */
    protected function _getCoordinator()
    {
        return $this->coordinator;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
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
     * @inheritdoc
     * @since [*next-version*]
     * @return AbstractTester This instance.
     */
    public function runAll()
    {
        $this->_beforeRunAll();
        $this->_runAll();
        $this->_afterRunAll();

        return $this;
    }

    /**
     * Low-level running of tests in this tester's suites.
     *
     * @since [*next-version*]
     * @return AbstractTester This instance.
     */
    protected function _runAll()
    {
        foreach ($this->_getSuites() as $_code => $_suite) {
            /* @var $_suite SuiteInterface */
            $this->_beforeRunSuite($_suite);
            $_suite->runAll();
            $this->_afterRunSuite($_suite);
        }

        return $this;
    }

    /**
     * Executes after tests in one of this tester's suites are executed.
     *
     * @since [*next-version*]
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
     * @return AbstractTester This instance.
     */
    protected function _afterRunAll()
    {
        $this->_getCoordinator()->afterRunAllSuites($this, $this);

        return $this;
    }

    /**
     * Executes before tests in this tester's suites are executed.
     *
     * @since [*next-version*]
     * @return AbstractTester This instance.
     */
    protected function _beforeRunAll()
    {
        $this->_getCoordinator()->beforeRunAllSuites($this, $this);

        return $this;
    }

    /**
     * @since [*next-version*]
     * @return Suite\SuiteInterface[]
     */
    protected function _getSuites()
    {
        return $this->suites;
    }
}
