<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Assertion;
use Dhii\Collection;
use UnexpectedValueException;
use Countable;

/**
 * Common functionality for test result sets.
 *
 * @since [*next-version*]
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
     * @since [*next-version*]
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
     * @since [*next-version*]
     */
    public function getTestStatusCodes()
    {
        return AbstractResult::getAllTestStatusCodes();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getTimeTaken()
    {
        return $this->_getStats('time_usage');
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getMemoryTaken()
    {
        return $this->_getStats('memory_usage');
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function getTestCount()
    {
        return array_sum($this->_getStats('test_count'));
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
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
     * @since [*next-version*]
     */
    public function getAssertionCount()
    {
        return $this->_getStats('assertion_count');
    }

    /**
     * Determines if given value is a valid item for this collection.
     *
     * @since [*next-version*]
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
     * @since [*next-version*]
     */
    public function getResults()
    {
        return $this->getItems();
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
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
     * @since [*next-version*]
     */
    public function search($eval)
    {
        return $this->_search($eval, $this);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    public function count()
    {
        return $this->getTestCount();
    }
}
