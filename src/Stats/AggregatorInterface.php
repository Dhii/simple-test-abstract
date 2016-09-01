<?php

namespace Dhii\SimpleTest\Stats;

/**
 * Something that can act as a stats aggregator.
 *
 * A stats aggregator is something that calculates totals based on individual values.
 *
 * @since [*next-version*]
 */
interface AggregatorInterface
{
    /**
     * Aggregates stats from a set of items.
     *
     * @since [*next-version*]
     *
     * @param mixed[]              $totals Existing totals.
     * @param mixed[]|\Traversable $items  A list of items to aggregate the totals from.
     *
     * @return mixed[] The new totals.
     */
    public function aggregate($totals, $items);
}
