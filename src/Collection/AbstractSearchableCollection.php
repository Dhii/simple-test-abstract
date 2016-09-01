<?php

namespace Dhii\SimpleTest\Collection;

use InvalidArgumentException;

/**
 * Common functionality for collection searching.
 *
 * @since [*next-version*]
 */
abstract class AbstractSearchableCollection extends AbstractCallbackCollectionBase
{
    /**
     * Search the items of a collection according to arbitrary criteria.
     *
     * @since [*next-version*]
     *
     * @param callable $eval See {@see SearchableCollectionInterface::search()} for details on the callback.
     * @param object[]|\Traversable
     *
     * @throws InvalidArgumentException If the evaluator is not callable.
     *
     * @return object[]|\Traversable
     */
    protected function _search($eval, $items = null)
    {
        if (is_null($items)) {
            $items = $this;
        }
        $results = array();
        foreach ($this->_each($eval, $items) as $_key => $_item) {
            if ($this->_isValidSearchResult($_item)) {
                $results[$_key] = $_item;
            }
        }

        return $this->_createSearchResultsIterator($results);
    }

    /**
     * Creates an iterator of set of items that are are result of a search.
     *
     * @since [*next-version*]
     *
     * @param mixed[] The array of items.
     *
     * @return AbstractSearchableCollection The new collection that contains search results.
     */
    protected function _createSearchResultsIterator($results)
    {
        $class    = get_class($this);
        $instance = new $class();
        /* @var $instance AbstractSearchableCollection */
        $instance->_setItems($results);

        return $instance;
    }

    /**
     * Determines whether an item is a valid search item.
     *
     * @since [*next-version*]
     *
     * @param mixed $item The item to validate.
     *
     * @return bool True if item is a valid search result item; false otherwise.
     */
    protected function _isValidSearchResult($item)
    {
        return $this->_isValidItem($item);
    }
}
