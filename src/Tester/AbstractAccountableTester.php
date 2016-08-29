<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for testers that can have its test and assertion stats retrieved.
 *
 * @since [*next-version*]
 */
class AbstractAccountableTester extends AbstractTester implements
    Test\AccountableInterface,
    Assertion\AccountableInterface,
    Test\UsageAccountableInterface
{
    protected $memoryTaken;
    protected $timeTaken;

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getAssertionCount()
    {
        $count = 0;
        foreach ($this->_getSuites() as $_code => $_suite) {
            /* @var $_suite Suite\SuiteInterface */
            if ($_suite instanceof Assertion\AccountableInterface) {
                $count += $_suite->getAssertionCount();
            }
        }

        return $count;
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
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTimeTaken()
    {
        return $this->timeTaken;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTestCount()
    {
        $count = 0;
        foreach ($this->_getSuites() as $_code => $_suite) {
            /* @var $_suite Suite\SuiteInterface */
            if ($_suite instanceof Test\AccountableInterface) {
                $count += $_suite->getTestCount();
            }
        }

        return $count;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getTestCountByStatus($status)
    {
        $count = 0;
        foreach ($this->_getSuites() as $_code => $_suite) {
            /* @var $_suite Suite\SuiteInterface */
            if ($_suite instanceof Test\AccountableInterface) {
                $count += $_suite->getTestCountByStatus($status);
            }
        }

        return $count;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    protected function _runAll()
    {
        $timeBeforeRun = microtime(true);
        $memoryBeforeRun = memory_get_usage();
        parent::_runAll();
        $this->_setMemoryTaken(memory_get_usage() - $memoryBeforeRun);
        $this->_setTimeTaken(microtime(true) - $timeBeforeRun);
    }

    /**
     * Set the amount of memory taken by this tester.
     *
     * @since [*next-version*]
     * @param int $bytes The amount of memory, in bytes, taken by this tester.
     * @return AbstractAccountableTester This instance.
     */
    protected function _setMemoryTaken($bytes)
    {
        $this->memoryTaken = $bytes;

        return $this;
    }

    /**
     * Set the amount of time taken by this tester.
     *
     * @since [*next-version*]
     * @param float $seconds The amount of time, in seconds, taken by this tester.
     * @return AbstractAccountableTester This instance.
     */
    protected function _setTimeTaken($seconds)
    {
        $this->timeTaken = $seconds;

        return $this;
    }
}
