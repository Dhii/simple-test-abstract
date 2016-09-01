<?php

namespace Dhii\SimpleTest\Stats;

use RuntimeException;

/**
 * Common functionality for stats aggregators.
 *
 * @since [*next-version*]
 */
abstract class AbstractAggregator implements AggregatorInterface
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @param mixed[]              $totals Current totals.
     * @param mixed[]|\Traversable $items  The items, which to aggregate from.
     *
     * @return mixed[] The new totals.
     */
    public function aggregate($totals, $items)
    {
        $totals      = $this->_normalizeTotals($totals);
        $calculators = $this->_getCalculators();
        $stats       = $this->_aggregateStats($totals, $items, $calculators);

        return $stats;
    }

    /**
     * Normalizes totals.
     *
     * If the given totals contain numeric keys, the result will have the values of those keys as keys.
     * Null values will be added where stats are missing.
     *
     * Example:
     * `array('my_stat' => 123, 'other_stat');`
     * becomes
     * `array('my_stat' => 123, 'other_stat' => null)`
     *
     * @since [*next-version*]
     *
     * @param mixed[] $totals The totals to normalize.
     *
     * @return mixed[] An array where keys are stat names and values are stat values.
     */
    protected function _normalizeTotals($totals)
    {
        $newTotals = array();
        foreach ($totals as $_stat => $_total) {
            if (is_numeric($_stat)) {
                $_stat = $_total;
            }

            $newTotals[$_stat] = isset($totals[$_stat])
                    ? $totals[$_stat]
                    : null;
        }

        return $newTotals;
    }

    /**
     * Aggregates stats by applying appropriate calculators to all items.
     *
     * @since [*next-version*]
     *
     * @param mixed[]              $totals      Existing totals map, where key is stat name, and value is stat value.
     * @param mixed[]|\Traversable $items       A list of items to aggregate stats from.
     * @param callable[]           $calculators A map of calculators, where key is stat name, and value is a callable calculator.
     *
     * @throws RuntimeException If an aggregator is missing for a stat.
     *
     * @return mixed[] Aggregated stats.
     */
    protected function _aggregateStats($totals, $items, $calculators)
    {
        foreach ($items as $_item) {
            foreach ($newTotals as $_stat => $_total) {
                if (!isset($calculators[$_stat])) {
                    throw new RuntimeException(sprintf('Could not aggregate stat: No aggregator defined for stat "%1$s"', $_stat));
                }

                $newTotals[$_stat] = $this->_aggregateStat($newTotals, $_stat, $_item, $calculators[$_stat]);
            }
        }

        return $newTotals;
    }

    /**
     * Retrieve a map of available calculators.
     *
     * @since [*next-version*]
     *
     * @return callable[] A map of calculators, where key is stat code, and value is a callable calculator.
     */
    protected function _getCalculators()
    {
        return array();
    }

    /**
     * Aggregate a specific stat using an aggregator.
     *
     * @since [*next-version*]
     *
     * @param mixed[]      $totals     Current totals. Keys are stat names, values are stat values.
     * @param string       $stat       The code of the stat to aggregate.
     * @param object|array $source     The source of the stat, e.g. something from where to aggregate.
     * @param callable     $calculator The callable calculator that would deduce the new stat.
     *
     * @return mixed[] New stat value.
     */
    protected function _aggregateStat($totals, $stat, $source, $calculator)
    {
        return $this->_applyCalculator($totals, $stat, $source, $calculator);
    }

    /**
     * Apply a stat aggregator to a set of totals.
     *
     * @since [*next-version*]
     *
     * @param mixed[]      $totals     Current totals. Keys are stat names, values are stat values.
     * @param string       $stat       The code of the stat to aggregate.
     * @param object|array $source     The source of the stat, e.g. something from where to aggregate.
     * @param callable     $calculator The callable calculator that would deduce the new stat.
     *
     * @return mixed[] New stat value.
     */
    protected function _applyCalculator($totals, $stat, $source, $calculator)
    {
        if (!is_callable($calculator)) {
            throw new UnexpectedValueException(sprintf('Could not apply calculator for stat "%1$s": must be callable', $stat));
        }

        if (!isset($totals[$stat])) {
            $totals[$stat] = 0;
        }

        return call_user_func_array($calculator, array($totals, $stat, $source));
    }
}