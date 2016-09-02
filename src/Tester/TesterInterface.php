<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Suite;

/**
 * Something that can act as a tester.
 *
 * A tester is responsible for managing suites of tests, and collecting their run stats.
 *
 * @since [*next-version*]
 */
interface TesterInterface
{
    /**
     * Adds a suite to be run by this tester.
     *
     * @since [*next-version*]
     *
     * @param Suite\SuiteInterface $suite The suite to add.
     */
    public function addSuite(Suite\SuiteInterface $suite);

    /**
     * Runs all suites of tests in this tester.
     *
     * @since [*next-version*]
     *
     * @return Test\ResultSetInterface A list containing results of all tests that were run.
     */
    public function runAll();
}
