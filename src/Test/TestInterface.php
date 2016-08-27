<?php

namespace Dhii\SimpleTest\Test;

/**
 * Something that can act like a test.
 *
 * @since [*next-version*]
 */
interface TestInterface extends TestBaseInterface
{

    /**
     * Set the code for this test's suite.
     *
     * @since [*next-version*]
     * @param string $code The code of the suite to which this case belongs.
     */
    public function setSuiteCode($code);
}
