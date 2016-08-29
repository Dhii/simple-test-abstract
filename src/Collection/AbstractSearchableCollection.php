<?php

namespace Dhii\SimpleTest\Collection;

/**
 * Common functionality for collection searching.
 *
 * @since [*next-version*]
 */
abstract class AbstractSearchableCollection extends AbstractIterableCollection
{
    /**
     * Search the items of a collection according to arbitrary criteria.
     *
     * @since [*next-version*]
     *
     * @param callable $eval A callable that evaluates an item to determine whether it matches a criteria.
     *                       This callable must return the item passed as the first argument, the validity of which will be evaluated by {@see _isValidItem()}, if it is a match.
     *                       The second argument is a boolean value passed by reference which, if set to false, will prevent any further evaluation, and cause the search to stop.
     * @param object[]|\Traversable
     *
     * @throws \InvalidArgumentException If the evaluator is not callable.
     *
     * @return object[]\|\Traversable
     */
    protected function _search($eval, $items = null)
    {
        if (!is_callable($eval)) {
            throw new \InvalidArgumentException('Could not search for test: The evaluator is not callable');
        }

        if (is_null($items)) {
            $items = $this->getItems();
        }
        $results    = array();
        $isContinue = true;
        foreach ($items as $_key => $_item) {
            /* @var $_test Test\ResultInterface */
            $item = call_user_func_array($eval, array($_item, &$isContinue));

            if ($this->_isValidItem($item)) {
                $results[$this->_getItemKey($item)] = $item;
            }

            if (!$isContinue) {
                break;
            }
        }

        return $results;
    }
}
