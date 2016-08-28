<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Runner;
use Dhii\SimpleTest\Coordinator;

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
     * @param Coordinator\CoordinatorInterface $coordinator The coordinator to be used by this instance.
     */
    public function __construct($code, Runner\RunnerInterface $runner, Coordinator\CoordinatorInterface $coordinator)
    {
        $this->_setCode($code)
                ->_setRunner($runner)
                ->_setCoordinator($coordinator);
    }
}
