<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Stats;

/**
 * A default implementation of a test result set.
 *
 * @since [*next-version*]
 */
class ResultSet extends AbstractResultSet
{
    /**
     * @since [*next-version*]
     *
     * @param mixed[]|\Traversable      $results        The list of results that this set represents.
     * @param Stats\AggregatorInterface $statAggregator The stat aggregator to be used by this instance.
     */
    public function __construct($results, Stats\AggregatorInterface $statAggregator)
    {
        $this->_addItems($results);
        $this->_setStatAggregator($statAggregator);
    }

    /**
     * Creates a new collection that represents results of a search.
     *
     * Usually, this will be the same class as the object which the search is run on.
     *
     * @since [*next-version*]
     *
     * @param mixed[]|\Traversable $results The list of items that represents a search results.
     *
     * @return ResultSetInterface The result set that contains results of a search.
     */
    protected function _createSearchResultsIterator($results)
    {
        $class    = get_class($this);
        $instance = new $class(array(), $this->_getStatAggregator());
        /* @var $instance AbstractSearchableCollection */
        $instance->_setItems($results);

        return $instance;
    }
}
