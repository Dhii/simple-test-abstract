<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Coordinator;
use Dhii\SimpleTest\Runner;

/**
 * A default tester implementation.
 *
 * @since [*next-version*]
 */
class DefaultTester extends AbstractTester
{
    /**
     * @since [*next-version*]
     *
     * @param Coordinator\CoordinatorInterface $coordinator A writer that will be used by this tester to output data.
     */
    public function __construct(Coordinator\CoordinatorInterface $coordinator, Runner\RunnerInterface $runner)
    {
        $this->_setCoordinator($coordinator);
        $this->_setRunner($runner);
    }
}
