<?php

namespace Dhii\SimpleTest\Test;

use UnexpectedValueException;
use Dhii\SimpleTest\Stats;

/**
 * An implementation of a result set that abstracts access to
 * multiple result sets.
 *
 * @since [*next-version*]
 */
class ResultSetCollection extends AbstractResultSetCollection
{
    /**
     * @since [*next-version*]
     *
     * @param ResultSetInterface[]|\Traversable $items          A list of test result sets.
     * @param Stats\AggregatorInterface         $statAggregator The stat aggregator to be used by this instance.
     */
    public function __construct($items, Stats\AggregatorInterface $statAggregator)
    {
        $this->_addItems($items);
        $this->_setStatAggregator($statAggregator);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _createInnerIterator()
    {
        return new \AppendIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _validateItem($item)
    {
        if (!($item instanceof ResultSetInterface)) {
            throw new UnexpectedValueException(sprintf('Item must be a valid test result set'));
        }
    }
}
