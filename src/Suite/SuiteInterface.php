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
     *
     * @param Test\TestInterface $test The test to add.
     *                                 If a test with the same key already exists, it will be overwritten.
     */
    public function addTest(Test\TestInterface $test);

    /**
     * Add multiple tests.
     *
     * @since [*next-version*]
     *
     * @param Test\TestInterface[]|\Traversable $set A set of tests to add.
     */
    public function addTests($set);

    /**
     * Get this suite's unique identifier.
     *
     * @since [*next-version*]
     *
     * @return string This suite's code.
     *                This is unique tester-wide.
     */
    public function getCode();
}
