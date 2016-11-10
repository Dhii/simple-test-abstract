<?php

namespace Dhii\SimpleTest\Test;

use Iterator;
use AppendIterator;
use Dhii\Collection;

/**
 * Common functionality for test result set collections.
 *
 * Such collections work similarly to {@see AppendIterator} in that they iterate sequentially
 * over each result set; however, they also work as result sets, allowing searching and
 * stat aggregation in the way that any result set would.
 *
 * @since 0.1.0
 */
abstract class AbstractResultSetCollection extends AbstractResultSet implements Collection\SequenceIteratorIteratorInterface
{
    protected $innerIterator;

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     *
     * @return AppendIterator The iterator that will iterate over results in result sets.
     */
    public function getInnerIterator()
    {
        if (is_null($iterator = $this->_getInnerIterator())) {
            $this->_setInnerIterator($this->_createInnerIterator());
        }

        return $this->_getInnerIterator();
    }

    /**
     * Assigns the inner iterator instance.
     *
     * @since 0.1.0
     *
     * @param Iterator $iterator The iterator to set.
     *
     * @return AbstractTestResultSet This instance.
     */
    protected function _setInnerIterator(AppendIterator $iterator)
    {
        $this->innerIterator = $iterator;

        return $this;
    }

    /**
     * Retrieves the inner iterator instance.
     *
     * @since 0.1.0
     *
     * @return AppendIterator|null
     */
    protected function _getInnerIterator()
    {
        return $this->innerIterator;
    }

    /**
     * Creates a new instance of the inner iterator.
     *
     * @since 0.1.0
     *
     * @return AppendIterator The inner append iterator.
     */
    abstract protected function _createInnerIterator();

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     *
     * @param ResultSet $resultSet The result set to add.
     */
    protected function _addItem($resultSet)
    {
        $this->getInnerIterator()->append($resultSet);

        return true;
    }

    /**
     * The actual instances of {@see ResultSetInterface}.
     *
     * Iterating over this instance MUST return results of the inner iterator.
     *
     * @since 0.1.0
     */
    public function getItems()
    {
        return $this->getArrayIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getArrayIterator()
    {
        return $this->getInnerIterator()->getArrayIterator();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getIteratorIndex()
    {
        return $this->getInnerIterator()->getIteratorIndex();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    protected function _getItemsForCache()
    {
        return $this->getInnerIterator();
    }
}
