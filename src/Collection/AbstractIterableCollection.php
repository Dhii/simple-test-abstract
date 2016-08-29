<?php

namespace Dhii\SimpleTest\Collection;

/**
 * Common functionality for collections that can be iterated over in a foreach loop.
 *
 * Caches items on rewind, allowing convenient auto-generation of items,
 * while still having performance in the loop.
 *
 * @since [*next-version*]
 */
abstract class AbstractIterableCollection extends AbstractCollection implements \Iterator
{
    protected $itemIndex = 0;
    protected $cachedItems = null;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function current()
    {
        return current($this->_getCachedItems());
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function key()
    {
        return key($this->_getCachedItems());
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function next()
    {
        next($this->_getCachedItems());
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function rewind()
    {
        $this->_clearItemCache();
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function valid()
    {
        return key($this->_getCachedItems()) !== null;
    }

    /**
     * Retrieve items that are cached for iteration.
     *
     * If no items are cached, populates the cache first.
     *
     * @since [*next-version*]
     * @return array The array of items.
     */
    protected function &_getCachedItems()
    {
        if (is_null($this->cachedItems)) {
            $this->cachedItems = $this->_getItemsForCache();
            reset($this->cachedItems);
        }

        return $this->cachedItems;
    }

    /**
     * Retrieves items that are prepared to be cached and worked with.
     *
     * @since [*next-version*]
     * @return array The array of prepared items.
     */
    protected function _getItemsForCache()
    {
        $items = $this->getItems();
        return $items instanceof \Traversable
                ? iterator_to_array($items, true)
                : $items;
    }

    /**
     * Clears and resents the iterable item cache.
     *
     * @since [*next-version*]
     * @return AbstractIterableCollection This instance.
     */
    protected function _clearItemCache()
    {
        $this->cachedItems = null;

        return $this;
    }
}
