<?php

namespace Dhii\SimpleTest\Coordinator;

/**
 * A default coordinator implementation.
 *
 * @since [*next-version*]
 */
class AbstractCoordinator implements CoordinatorInterface
{
    /**
     * Silences calls to non-existing methods.
     *
     * Triggers a "catch-all" `_any()` method.
     *
     * @since [*next-version*]
     *
     * @param string $name      Name of the non-existing method.
     * @param array  $arguments The arguments for the method.
     */
    public function __call($name, $arguments)
    {
        $this->_any($name, array_shift($arguments), array_shift($arguments));
    }

    /**
     * Handles calls to a non-defined method.
     *
     * @since [*next-version*]
     *
     * @param string $target The method that was called
     * @param mixed  $data   The data to handle.
     * @param object $source The object that called called the method.
     */
    protected function _any($target, $data = null, $source = null)
    {
    }
}
