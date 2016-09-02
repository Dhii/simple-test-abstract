<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Coordinator;
use Dhii\SimpleTest\Runner;
use Dhii\SimpleTest\Collection;
use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Stats;

/**
 * A default tester implementation.
 *
 * @since [*next-version*]
 */
class Tester extends AbstractTester
{
    protected $statAggregator;

    /**
     * @since [*next-version*]
     *
     * @param Coordinator\CoordinatorInterface $coordinator A writer that will be used by this tester to output data.
     */
    public function __construct(
            Coordinator\CoordinatorInterface $coordinator,
            Runner\RunnerInterface $runner,
            Stats\AggregatorInterface $statAggregator
    ) {
        $this->_setCoordinator($coordinator);
        $this->_setRunner($runner);
        $this->_setStatAggregator($statAggregator);
    }

    /**
     * Assign the stat aggregator to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Stats\AggregatorInterface $statAggregator The stat aggregator that will be assigned to test results.
     *
     * @return Tester This instance.
     */
    protected function _setStatAggregator(Stats\AggregatorInterface $statAggregator)
    {
        $this->statAggregator = $statAggregator;

        return $this;
    }

    /**
     * Retrieve the stat aggregator used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Stats\AggregatorInterface The stat aggregator that is used by this instance.
     */
    protected function _getStatAggregator()
    {
        return $this->statAggregator;
    }

    /**
     * Create a new iterator of test result sets.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface[]|\Traversable $results A traversible list of result sets.
     *
     * @return Collection\SequenceIteratorIteratorInterface|Test\ResultSetInterface The list of result sets.
     */
    protected function _createResultSetIterator($results)
    {
        $iterator = new Test\ResultSetCollection($results, $this->_getStatAggregator());

        return $iterator;
    }
}
