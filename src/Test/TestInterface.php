<?php

namespace Dhii\SimpleTest\Test;

/**
 * Represents a test.
 *
 * @since [*next-version*]
 */
interface TestInterface extends TestBaseInterface
{

    /**
     * Set the code for this test's suite.
     *
     * @param string The code of the suite to which this case belongs.
     * @since [*next-version*]
     */
    public function setSuiteCode($code);
}
