<?php

namespace Dhii\SimpleTest\Test;

/**
 * A default result implementation.
 *
 * @since [*next-version*]
 */
class DefaultResult extends AbstractAccountableResult
{
    /**
     * @since [*next-version*]
     *
     * @param string $caseName       The name of the test case class, to which the test belongs.
     * @param string $methodName     The name of the test method.
     * @param string $key            The suite-wide unique identifier of the test.
     * @param mixed  $message        The result message.
     * @param string $status         The status code of the test result.
     * @param int    $assertionCount Number of assertions made in the test.
     * @param string $suiteCode      Code name of the suite, to which the test belongs.
     * @param string $runnerCode     Code name of the runner that ran the test.
     * @param float  $time           The time, in seconds, that was taken to run the test.
     * @param int    $memory         The memory, in bytes, that was taken to run the test.
     */
    public function __construct($caseName, $methodName, $key, $message, $status, $assertionCount, $suiteCode, $runnerCode, $time, $memory)
    {
        $this->_setCaseName($caseName)
                ->_setMethodName($methodName)
                ->_setKey($key);
        $this->_setMessage($message)
                ->_setStatus($status)
                ->_setAssertionCount($assertionCount)
                ->_setSuiteCode($suiteCode)
                ->_setRunnerCode($runnerCode)
                ->_setTimeTaken($time)
                ->_setMemoryTaken($memory);
    }
}
