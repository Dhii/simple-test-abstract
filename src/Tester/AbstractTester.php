<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Suite;
use Dhii\SimpleTest\Writer;

/**
 * Common functionality for testers.
 *
 * @since [*next-version*]
 */
abstract class AbstractTester implements TesterInterface
{
    protected $suites;
    protected $writer;

    /**
     * Sets a writer to be used by this tester.
     *
     * @since [*next-version*]
     * @param Writer\WriterInterface $writer A writer that will be used by this tester to output data.
     * @return AbstractTester This instance.
     */
    protected function _setWriter(Writer\WriterInterface $writer)
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     * @return AbstractTester This instance.
     */
    public function addSuite(Suite\SuiteInterface $suite)
    {
        $this->suites[$suite->getCode()] = $suite;

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
    protected function _beforeRunSuite(Suite\SuiteInterface $_suite)
    {
        return $this;
    }

    /**
     * Executes after tests in one of this tester's suites are executed.
     *
     * @since [*next-version*]
     * @return AbstractTester This instance.
     */
    protected function _afterRunSuite(Suite\SuiteInterface $_suite)
    {
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
