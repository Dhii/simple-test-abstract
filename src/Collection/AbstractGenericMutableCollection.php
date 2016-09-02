<?php

namespace Dhii\SimpleTest\Collection;

/**
 * Common functionality for collections that can have its item set changed.
 *
 * @since [*next-version*]
 */
abstract class AbstractGenericMutableCollection extends AbstractGenericCollection implements MutableCollectionInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function addItem($item)
    {
        $result = $this->_addItem($item);
        $this->_clearItemCache();
        $this->_resetStats();

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function addItems($items)
    {
        $result = $this->_addItems($items);
        $this->_clearItemCache();
        $this->_resetStats();

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function removeItem($item)
    {
        $result = $this->_removeItem($item);
        $this->_clearItemCache();
        $this->_resetStats();

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function removeItemByKey($key)
    {
        $result = $this->_removeItem($item);

        return $result;
    }
}
