<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Assertion;
use Dhii\Collection;
use UnexpectedValueException;
use Countable;

/**
 * Common functionality for test result sets.
 *
 * @since 0.1.0
 */
abstract class AbstractResultSet extends Collection\AbstractSearchableCollection implements
    Countable,
    ResultSetInterface,
    AccountableInterface,
    UsageAccountableInterface,
    Assertion\AccountableInterface,
    Collection\SearchableCollectionInterface
{
    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    protected function _getAggregatableStatCodes()
    {
        return array(
            'assertion_count',
            'time_usage',
            'memory_usage',
            'test_count',
        );
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTestStatusCodes()
    {
        return AbstractResult::getAllTestStatusCodes();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTimeTaken()
    {
        return $this->_getStats('time_usage');
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getMemoryTaken()
    {
        return $this->_getStats('memory_usage');
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTestCount()
    {
        $counts = $this->_getStats('test_count');

        return is_array($counts) ? array_sum($counts) : $counts;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getTestCountByStatus($status)
    {
        $totals = $this->_getStats('test_count');

        return isset($totals[$status])
                ? $totals[$status]
                : null;
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getAssertionCount()
    {
        return $this->_getStats('assertion_count');
    }

    /**
     * Determines if given value is a valid item for this collection.
     *
     * @since 0.1.0
     *
     * @param mixed $item The item to validate.
     *
     * @throws UnexpectedValueException If item is not valid.
     */
    protected function _validateItem($item)
    {
        if (!($item instanceof ResultInterface)) {
            throw new UnexpectedValueException(sprintf('Item must be a valid test result'));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getResults()
    {
        return $this->getItems();
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function getResultsByStatus($status)
    {
        return $this->_search(function ($key, ResultInterface $item, &$isContinue) use ($status) {
            if ($item->getStatus() === $status) {
                return $item;
            }
        });
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function search($eval)
    {
        return $this->_search($eval, $this);
    }

    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
     */
    public function count()
    {
        return $this->getTestCount();
    }
}
