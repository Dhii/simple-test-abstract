<?php

namespace Dhii\SimpleTest\Test;

/**
 * Represents a test result.
 *
 * @since [*next-version*]
 */
interface ResultInterface extends TestBaseInterface
{
    const FAILURE = 'failed';
    const ERROR   = 'errored';
    const SUCCESS = 'success';

    /**
     * Retrieve the status code of this test.
     *
     * A status represents the outcome of a test.
     *
     * @since [*next-version*]
     *
     * @return string A string that represents this test's status.
     */
    public function getStatus();

    /**
     * Retrieve a list of possible status codes.
     *
     * @since [*next-version*]
     *
     * @return string[] A numeric array, each value of which is unique and reresents a status code.
     */
    public function getTestStatusCodes();

    /**
     * Get the message of this test.
     *
     * @since [*next-version*]
     *
     * @return mixed Something that represents a message.
     */
    public function getMessage();

    /**
     * Retrieve whether or not this test was successful.
     *
     * @since [*next-version*]
     *
     * @return bool True if the test passed; false otherwise.
     */
    public function isSuccessful();

    /**
     * Retrieve the code of the runner that ran this test.
     *
     * @since [*next-version*]
     *
     * @return string The code of the runner that ran this test.
     */
    public function getRunnerCode();
}
