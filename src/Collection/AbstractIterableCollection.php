<?php

namespace Dhii\SimpleTest\Collection;

use RuntimeException;
use UnexpectedValueException;

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
    protected $itemIndex   = 0;
    protected $cachedItems = null;

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function current()
    {
        return $this->_arrayCurrent($this->_getCachedItems());
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function key()
    {
        return $this->_arrayKey($this->_getCachedItems());
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function next()
    {
        $this->_arrayNext($this->_getCachedItems());
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function rewind()
    {
        $this->_clearItemCache();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function valid()
    {
        return $this->_arrayKey($this->_getCachedItems()) !== null;
    }

    protected function _count()
    {
        return $this->_arrayCount($this->_getCachedItems());
    }

    /**
     * Retrieve items that are cached for iteration.
     *
     * If no items are cached, populates the cache first.
     *
     * @since [*next-version*]
     *
     * @return array The array of items.
     */
    protected function &_getCachedItems()
    {
        if (is_null($this->cachedItems)) {
            $this->cachedItems = $this->_getItemsForCache();
            $this->_arrayRewind($this->cachedItems);
        }

        return $this->cachedItems;
    }

    /**
     * Retrieves items that are prepared to be cached and worked with.
     *
     * @since [*next-version*]
     *
     * @return array The array of prepared items.
     */
    protected function _getItemsForCache()
    {
        $items = $this->getItems();

        return $items;
    }

    /**
     * Clears and resents the iterable item cache.
     *
     * @since [*next-version*]
     *
     * @return AbstractIterableCollection This instance.
     */
    protected function _clearItemCache()
    {
        $this->cachedItems = null;

        return $this;
    }

    /**
     * Retrieve the current element from a list.
     *
     * @since [*next-version*]
     *
     * @param array|\Traversable $array The list to get the current element of.
     *
     * @return mixed The current element in the list.
     */
    protected function _arrayCurrent(&$array)
    {
        return $array instanceof \Traversable
            ? $this->_getIterator($array)->current()
            : current($array);
    }

    /**
     * Retrieve the current key from a list.
     *
     * @since [*next-version*]
     *
     * @param array|\Traversable $array The list to get the current key of.
     *
     * @return string|int The current key in the list.
     */
    protected function _arrayKey(&$array)
    {
        return $array instanceof \Traversable
            ? $this->_getIterator($array)->key()
            : key($array);
    }

    /**
     * Move the pointer of the list to the beginning.
     *
     * @since [*next-version*]
     *
     * @param array|\Traversable $array The list to rewind.
     *
     * @return mixed|bool The value of the first list item.
     */
    protected function _arrayRewind(&$array)
    {
        return $array instanceof \Traversable
            ? $this->_getIterator($array)->rewind()
            : reset($array);
    }

    /**
     * Move the pointer of the list forward and return the element there.
     *
     * @since [*next-version*]
     *
     * @param array|\Traversable $array The list to move the pointer of.
     *
     * @return mixed|null The element at the next position in the list.
     */
    protected function _arrayNext(&$array)
    {
        return $array instanceof \Traversable
            ? $this->_getIterator($array)->next()
            : next($array);
    }

    /**
     * Get the amount of all elements in the given list.
     *
     * @since [*next-version*]
     *
     * @param array|\Countable|\Traversable $array The list to get the count of
     *
     * @throws RuntimeException If the given list is not something that can be counted.
     *
     * @return int The number of items in the list.
     */
    public function _arrayCount(&$list)
    {
        if (is_array($list)) {
            return count($list);
        }

        if ($list instanceof \Countable) {
            return $list->count();
        }

        if ($list instanceof \Traversable) {
            $count = 0;
            $list  = $this->_getIterator($list);
            foreach ($list as $_item) {
                ++$count;
            }

            return $count;
        }

        throw new RuntimeException(sprintf('Could not count elements: the given list is not someting that can be counted'));
    }

    /**
     * Retrieve the bottom-most iterator of this iterator.
     *
     * If this is an iterator, gets itself.
     * If this is an {@see \IteratorAggregate}, return its inner-most iterator, recursively.
     *
     *
     * @since [*next-version*]
     *
     * @param \Traversable $iterator An iterator.
     *
     * @return \Iterator The final iterator.
     */
    protected function _getIterator(\Traversable $iterator)
    {
        if ($iterator instanceof \Iterator) {
            return $iterator;
        }

        if (!($iterator instanceof \IteratorAggregate)) {
            throw new UnexpectedValueException(sprintf('Could not retrieve iterator'));
        }

        $this->_getIterator($iterator->getIterator());
    }
}
