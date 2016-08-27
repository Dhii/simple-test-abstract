<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Test;

/**
 * Something that can act as a test suite.
 *
 * @since [*next-version*]
 */
interface SuiteInterface
{

    /**
     * Add a single test.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test to add.
     *  If a test with the same key already exists, it will be overwritten.
     */
    public function addTest(Test\TestInterface $test);

    /**
     * Add multiple tests.
     *
     * @since [*next-version*]
     * @param Test\TestInterface[]|\Traversable $set A set of tests to add.
     */
    public function addTests($set);

    /**
     * Get this suite's unique identifier.
     *
     * @since [*next-version*]
     * @return string This suite's code.
     *  This is unique tester-wide.
     */
    public function getCode();

    /**
     * Run all the tests in this suite.
     *
     * @since [*next-version*]
     * @return int Number of tests that were run.
     */
    public function runAll();

    /**
     * Retrieve all results of tests that were run.
     *
     * @since [*next-version*]
     * @return Test\ResultInterface[] All results for tests ran by this suite.
     */
    public function getResults();

    /**
     * Retrieve a result for a test with the specified code.
     *
     * @since [*next-version*]
     * @param string $code Code of the test, for which to retrieve the result.
     * @return Test\ResultInterface|null The test result, or null if no such result exists.
     */
    public function getResult($code);
}
