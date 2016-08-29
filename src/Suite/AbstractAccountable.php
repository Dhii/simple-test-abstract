<?php

namespace Dhii\SimpleTest\Suite;

use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Assertion;
use Dhii\SimpleTest\Runner;

/**
 * Common functionality for suites that can have their test, assertion, and usage stats retrieved.
 *
 * @since [*next-version*]
 */
abstract class AbstractAccountable extends AbstractSuite implements
    Assertion\AccountableInterface,
    Test\AccountableInterface,
    Test\UsageAccountableInterface
{
    protected $cases = array();
    protected $caseSets = array();
    protected $code;
    protected $tests;
    protected $assertionMaker;
    protected $runner;
    protected $timeTaken;
    protected $memoryTaken;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTimeTaken()
    {
        return $this->timeTaken;
    }

    /**
     * Set the amount of time taken by this suite to run the tests.
     *
     * @since [*next-version*]
     * @param float $seconds The amount of time, in seconds, taken to run tests of this suite.
     * @return AbstractExtended This instance.
     */
    protected function _setTimeTaken($seconds)
    {
        $this->timeTaken = $seconds;

        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getMemoryTaken()
    {
        return $this->memoryTaken;
    }
    /**
     * Set the amount of memory taken by this suite to run the tests.
     *
     * @since [*next-version*]
     * @param int $bytes The amount of memory, in bytes, taken to run tests of this suite.
     * @return AbstractExtended This instance.
     */
    protected function _setMemoryTaken($bytes)
    {
        $this->memoryTaken = $bytes;

        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    protected function _runAll()
    {
        $memoryBeforeTests = memory_get_usage();
        $timeBeforeTests = microtime(true);
        parent::_runAll();
        $this->_setMemoryTaken(memory_get_usage() - $memoryBeforeTests);
        $this->_setTimeTaken(microtime(true) - $timeBeforeTests);

        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTestCount()
    {
        return count($this->results);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTestCountByStatus($status)
    {
        return count($this->_getResultsByStatus($status));
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getAssertionCount()
    {
        $count = 0;
        foreach ($this->getResults() as $_code => $_result) {
            /* @var $_result Test\ResultInterface */
            if ($_result instanceof Assertion\AccountableInterface) {
                $count += $_result->getAssertionCount();
            }
        }

        return $count;
    }

    /**
     * Retrieve results that match a given status
     *
     * @since [*next-version*]
     * @param string $status The status code to search for.
     * @return Test\ResultInterface[] Results that match the specified status.
     */
    protected function _getResultsByStatus($status)
    {
        return $this->_search(function(Test\ResultInterface $result, &$isContinue) use ($status) {
            return $result->getStatus() === $status
                    ? $result
                    : null;
        }, $this->getResults());
    }

    /**
     * @since [*next-version*]
     * @return Runner\RunnerInterface
     */
    protected function _getRunner()
    {
        return $this->runner;
    }

    /**
     * @since [*next-version*]
     * @param Runner\RunnerInterface $runner
     * @return AbstractAccountable
     */
    protected function _setRunner(Runner\RunnerInterface $runner)
    {
        $this->runner = $runner;
        return $this;
    }
}
