<?php

namespace Dhii\SimpleTest\Test;

/**
 * Represents a test.
 *
 * @since [*next-version*]
 */
interface TestInterface
{
    const FAILURE = 'failed';
    const ERROR = 'errored';
    const SUCCESS = 'success';

    /**
     * Retrieve the class name of this test's test case.
     *
     * @since [*next-version*]
     * @return string Name of the test case, of which this instance represents a test.
     */
    public function getCaseName();

    /**
     * Retrieve the name of this test's method.
     *
     * @since [*next-version*]
     * @return string Name of the test method, which is represented by this instance.
     */
    public function getMethodName();

    /**
     * Retrieve the status code of this test.
     *
     * A status represents the outcome of a test.
     *
     * @since [*next-version*]
     * @return string A string that represents this test's status.
     */
    public function getStatus();

    /**
     * Sets the status code for this test.
     *
     * A status represents the outcome of a test.
     *
     * @param string A status for this test.
     * @since [*next-version*]
     */
    public function setStatus($status);

    /**
     * Retrieve the key of this test.
     *
     * A key is a test-suite-unique identifier.
     *
     * @since [*next-version*]
     * @return string The key of this test.
     */
    public function getKey();

    /**
     * Retrieve a list of possible status codes.
     *
     * @since [*next-version*]
     * @return string[] A numeric array, each value of which is unique and reresents a status code.
     */
    public function getStatusCodes();

    /**
     * Set the message for this test.
     *
     * @param mixed Something that represents a message.
     * @since [*next-version*]
     */
    public function setMessage($message);

    /**
     * Get the message of this test.
     *
     * @since [*next-version*]
     * @return mixed Something that represents a message.
     */
    public function getMessage();

    /**
     * Retrieve whether or not this test was successful.
     *
     * @since [*next-version*]
     * @return bool True if the test passed; false otherwise.
     */
    public function isSuccessful();

    /**
     * Retrieve the code of this test's suite.
     *
     * @since [*next-version*]
     * @return string The code of the suite to which this case belongs.
     */
    public function getSuiteCode();

    /**
     * Set the code for this test's suite.
     *
     * @param string The code of the suite to which this case belongs.
     * @since [*next-version*]
     */
    public function setSuiteCode($code);

    /**
     * Retrieve the code of the runner that ran this test.
     *
     * @since [*next-version*]
     * @return string The code of the runner that ran this test.
     */
    public function getRunnerCode();

    /**
     * Set the code of the runner that ran this test.
     *
     * @param string The code of the runner that ran this test.
     * @since [*next-version*]
     */
    public function setRunnerCode($code);

    /**
     * Set the amount of assertions that were made during this test.
     *
     * @param int The code of the runner that ran this test.
     * @since [*next-version*]
     */
    public function setAssertionCount($assertionCount);

    /**
     * Retrieve the amount of assertions that were made during this test.
     *
     * @since [*next-version*]
     * @return int The code of the runner that ran this test.
     */
    public function getAssertionCount();
}
