<?php

namespace Dhii\SimpleTest\Collection;

/**
 * Common functionality for generic collections that can have its items retrieved and checked.
 *
 * @since [*next-version*]
 */
abstract class AbstractGenericAccessibleCollection extends AbstractGenericCollection implements AccessibleCollectionInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getItem($key)
    {
        return $this->_getItem($key, null);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function hasItem($item)
    {
        return $this->_hasItem($item);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function hasItemKey($key)
    {
        return $this->_hasItemKey($key);
    }
}
