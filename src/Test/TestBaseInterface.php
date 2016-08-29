<?php

namespace Dhii\SimpleTest\Test;

/**
 * Something that can act like a test base.
 *
 * @since [*next-version*]
 */
interface TestBaseInterface
{
    /**
     * Retrieve the class name of this test's test case.
     *
     * @since [*next-version*]
     *
     * @return string Name of the test case, of which this instance represents a test.
     */
    public function getCaseName();

    /**
     * Retrieve the name of this test's method.
     *
     * @since [*next-version*]
     *
     * @return string Name of the test method, which is represented by this instance.
     */
    public function getMethodName();

    /**
     * Retrieve the key of this test.
     *
     * A key is a test-suite-unique identifier.
     *
     * @since [*next-version*]
     *
     * @return string The key of this test.
     */
    public function getKey();

    /**
     * Retrieve the code of this test's suite.
     *
     * @since [*next-version*]
     *
     * @return string The code of the suite to which this case belongs.
     */
    public function getSuiteCode();
}
