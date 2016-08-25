<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Assertion;
use Dhii\SimpleTest\Writer;

/**
 * Something that can act as a test suite.
 * 
 * @since [*next-version*]
 */
interface SuiteInterface extends
    Assertion\AccountableInterface,
    Test\AccountableInterface,
    Writer\WriterAwareInterface
{    
    /**
     * @since [*next-version*]
     * @return Test\TestInterface[]|\Traversable All tests in this suite,
     */
    public function getTests();
    
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
}
