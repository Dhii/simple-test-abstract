<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest\Stats;
use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for aggregators of test stats.
 *
 * @since [*next-version*]
 */
class AbstractAggregator extends Stats\AbstractAggregator
{
    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getCalculators()
    {
        return array(
            'assertion_count' => function ($totals, $code, $source) {
                if ($source instanceof Assertion\AccountableInterface) {
                    return $totals[$code] + $source->getAssertionCount();
                }

                return intval($totals[$code]);
            },

            'time_usage' => function ($totals, $code, $source) {
                if ($source instanceof UsageAccountableInterface) {
                    return $totals[$code] + $source->getTimeTaken();
                }

                return intval($totals[$code]);
            },

            'memory_usage' => function ($totals, $code, $source) {
                if ($source instanceof UsageAccountableInterface) {
                    return $totals[$code] + $source->getMemoryTaken();
                }

                return intval($totals[$code]);
            },

            'test_count' => function ($totals, $code, $source) {
                if ($source instanceof AccountableInterface) {
                    $counts = is_array($totals[$code])
                            ? $totals[$code]
                            : array();
                    $status = $source->getStatus();

                    $counts[$status] = 1 + (isset($counts[$status])
                            ? intval($counts[$status])
                            : 0);
                }

                return $counts;
            },
        );
    }
}
