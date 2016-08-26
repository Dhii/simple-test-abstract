<?php

namespace Dhii\SimpleTest;

/**
 * A default runner implementation.
 *
 * @since [*next-version*]
 */
class DefaultRunner extends AbstractRunner
{
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getCode()
    {
        return 'default';
    }
}
