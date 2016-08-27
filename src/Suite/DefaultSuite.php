<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Runner;

/**
 * A default test suite implementation.
 *
 * @since [*next-version*]
 */
class DefaultSuite extends AbstractAccountable
{
    /**
     * @since [*next-version*]
     * @param string $code This suite's unique code.
     * @param Runner\RunnerInterface $runner The runner that will run this suite's tests.
     */
    public function __construct($code, Runner\RunnerInterface $runner)
    {
        $this->_setCode($code)
                ->_setRunner($runner);
    }
}
