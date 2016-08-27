<?php

namespace Dhii\SimpleTest\Test;

/**
 * Common functionality for test collections.
 *
 * @since [*next-version*]
 */
abstract class AbstractCollection implements \Iterator
{
    protected $itemIndex = 0;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function current()
    {
        $index = $this->_getItemIndex();
        $key = $this->_getIndexKey($index);
        if (is_null($key)) {
            throw new \OutOfBoundsException(sprintf('Could not retrieve item at index %1$s: Index is out of bounds', $index));
        }

        $items = $this->_getItems();
        if (!isset($items[$key])) {
            throw new \OutOfBoundsException(sprintf('Could not retrieve item at index %1$s ($2$s): Item does not exist', $index, $key));
        }

        return $items[$key];
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function key()
    {
        return $this->_getItemIndex();
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function next()
    {
        $index = $this->_getItemIndex();
        $this->_setItemIndex(++$index);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function rewind()
    {
        $this->_setItemIndex(0);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function valid()
    {
        $index = $this->_getItemIndex();
        $key = $this->_getIndexKey($index);
        if (is_null($key)) {
            return false;
        }

        $items = $this->_getItems();
        return isset($items[$key]);
    }

    /**
     * Sets the internal position pointer.
     *
     * @since [*next-version*]
     * @param int $index The new position for the pointer.
     * @return AbstractSource This instance.
     */
    protected function _setItemIndex($index)
    {
        $this->itemIndex = $index;

        return $this;
    }

    /**
     * Sets the internal position pointer.
     *
     * @since [*next-version*]
     * @return int This internal pointer value.
     */
    protected function _getItemIndex()
    {
        return $this->itemIndex;
    }

    /**
     * Retrieve the key at the specified item index.
     *
     * The key can be the same if items are a numeric array.
     * If the items are stored as an associative array, however, this will
     * not be the case. This method allows working with assoc arrays still.
     *
     * @since [*next-version*]
     * @param int $index The index, for which to retrieve the key.
     * @return string|int The key at the specified index.
     */
    protected function _getIndexKey($index)
    {
        $keys = array_keys($this->_getItems());
        return isset($keys[$index])
            ? $keys[$index]
            : null;
    }

    /**
     * Retrieve all items in this collection
     *
     * @since [*next-version*]
     * @return mixed[]
     */
    abstract protected function _getItems();
}
