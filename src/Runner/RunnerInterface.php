<?php

namespace Dhii\SimpleTest\Runner;

use Dhii\SimpleTest\Test;

/**
 * Something that can act as a test runner.
 *
 * @since [*next-version*]
 */
interface RunnerInterface
{
    /**
     * Retrieve the code name of this runner.
     *
     * @since [*next-version*]
     * @return string This runner's code.
     */
    public function getCode();

    /**
     * Runs a test.
     *
     * @param Test\TestInterface $test The test to run.
     * @return Test\ResultInterface The result of the test.
     */
    public function run(Test\TestBaseInterface $test);
}
