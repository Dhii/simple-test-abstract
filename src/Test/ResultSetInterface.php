<?php

namespace Dhii\SimpleTest\Test;

/**
 * Something that can act as a search result set.
 *
 * @since [*next-version*]
 */
interface ResultSetInterface
{
    /**
     * Retrieves a list of all test results in this set.
     *
     * @since [*next-version*]
     *
     * @return ResultInterface[]|\Traversable
     */
    public function getResults();

    /**
     * Retrieves a list of test results which have the specified status.
     *
     * @since [*next-version*]
     *
     * @param string $status The status code, for which to retrieve results.
     *
     * @return ResultInterface[]|\Traversable A list of results for the specified status.
     */
    public function getResultsByStatus($status);
}
