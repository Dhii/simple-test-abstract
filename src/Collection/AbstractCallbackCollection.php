<?php

namespace Dhii\SimpleTest\Collection;

/**
 * Common functionality for callback collections.
 *
 * Ready to be extended and instantiated, with minimal or no modifications.
 *
 * @since [*next-version*]
 */
abstract class AbstractCallbackCollection extends AbstractCallbackCollectionBase implements CallbackIterableInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function each($callback)
    {
        return $this->_each($callback, $this);
    }
}
