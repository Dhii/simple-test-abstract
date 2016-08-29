<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Coordinator;

/**
 * A default tester implementation.
 *
 * @since [*next-version*]
 */
class DefaultTester extends AbstractAccountableTester
{
    /**
     * @since [*next-version*]
     * @param Coordinator\CoordinatorInterface $coordinator A writer that will be used by this tester to output data.
     */
    public function __construct(Coordinator\CoordinatorInterface $coordinator)
    {
        $this->_setCoordinator($coordinator);
    }
}
