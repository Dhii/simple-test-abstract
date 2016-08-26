<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Test;

/**
 * Most basic common test suite functionality.
 *
 * @since [*next-version*]
 */
abstract class AbstractSuite extends Test\AbstractSupervisor implements SuiteInterface
{
    protected $tests = array();

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
     * @return Test\TestInterface[]|\Traversable The tests in this suite.
     */
    protected function _getTests()
    {
        return $this->tests;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function addTest(Test\TestInterface $test)
    {
        $this->_addTest($test);

        return $this;
    }

    /**
     * Low-level single test adding.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test to add.
     * @return AbstractSuite This instance.
     */
    protected function _addTest(Test\TestInterface $test)
    {
        $test->setSuiteCode($this->getCode());
        $this->tests[$test->getKey()] = $test;

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
     * @param Test\TestInterface[]|\Traversable $tests The tests to add.
     * @return AbstractSuite This instance.
     */
    protected function _addTests($tests)
    {
        foreach ($tests as $_test) {
            $this->_addTest($_test);
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
     * @return string The code of this suite.
     */
    protected function _getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function runAll()
    {
        $this->_runAll();
    }

    /**
     * @since [*next-version*]
     */
    protected function _runAll()
    {
        foreach ($this->getTests() as $_test) {
            $this->_runTest($_test);
        }
    }

    /**
     * Runs a single test.
     *
     * Should not run the test directly, but use a runner instance, and
     * provide the test case with an assertion maker instance.
     *
     * @param Test\TestInterface $test The test to run.
     * @since [*next-version*]
     */
    abstract protected function _runTest(Test\TestInterface $test);
}
