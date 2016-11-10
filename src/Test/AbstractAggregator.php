<?php

namespace Dhii\SimpleTest\Test;

use Dhii\Stats;
use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for aggregators of test stats.
 *
 * @since 0.1.0
 */
class AbstractAggregator extends Stats\AbstractAggregator
{
    /**
     * {@inheritdoc}
     *
     * @since 0.1.0
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

                    foreach ($source->getTestStatusCodes() as $_status) {
                        $count = $source->getTestCountByStatus($_status);
                        $counts[$_status] = $count + (isset($counts[$_status])
                                ? intval($counts[$_status])
                                : 0);
                    }
                }

                return $counts;
            },
        );
    }
}
