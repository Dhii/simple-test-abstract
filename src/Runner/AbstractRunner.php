<?php

namespace Dhii\SimpleTest\Runner;

use Dhii\SimpleTest;
use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Writer;
use Dhii\SimpleTest\TestCase;
use Dhii\SimpleTest\Assertion;

/**
 * Common functionality for test runners.
 *
 * @since [*next-version*]
 */
abstract class AbstractRunner implements RunnerInterface
{
    protected $writer;
    protected $assertionMaker;

    /**
     * Sets a writer instance for this runner.
     *
     * @since [*next-version*]
     * @param Writer\WriterInterface $writer A writer that this runner should use to output data.
     * @return AbstractRunner This instance.
     */
    protected function _setWriter(Writer\WriterInterface $writer)
    {
        $this->writer = $writer;
        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * Sets an assertion maker instance for this runner.
     *
     * @since [*next-version*]
     * @param Assertion\MakerInterface $assertionMaker The assertion maker that this runner should pass to test cases that it runs.
     * @return AbstractRunner This instance.
     */
    protected function _setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;
        return $this;
    }

    /**
     * Retrieves the assertion maker instance used by this runner.
     *
     * @since [*next-version*]
     * @return Assertion\MakerInterface The assertion maker that this runner uses.
     */
    protected function _getAssertionMaker()
    {
        return $this->assertionMaker;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function getAssertionStatusCount($status = null)
    {
        return $this->_getAssertionMaker()->getAssertionStatusCount($status);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function run(Test\TestBaseInterface $test)
    {
        return $this->_run($test);
    }

    /**
     * Low-level running of a test.
     *
     * @since [*next-version*]
     * @param Test\TestBaseInterface $test The test to run.
     * @return Test\ResultInterface The result of the test run.
     */
    protected function _run(Test\TestBaseInterface $test)
    {
        $assertionMaker = $this->_getAssertionMaker();
        $countAssertions = $assertionMaker instanceof Assertion\AccountableInterface;
        if ($countAssertions) {
            $assertionCount = $assertionMaker->getAssertionCount();
        }
        $timeBeforeTest = microtime(true);
        $memoryBeforeTest = memory_get_usage();

        try {
            $className = $test->getCaseName();
            $methodName = $test->getMethodName();
            $case = new $className();

            if (!($case instanceof TestCase\CaseInterface)) {
                throw new SimpleTest\Exception(sprintf('Could not run test "%1$s": not a valid test case'));
            }

            if ($case instanceof TestCase\AssertiveInterface) {
                $case->setAssertionMaker($assertionMaker);
            }

            $this->_beforeTest($test);
            $case->beforeTest();
            $case->{$methodName}();
        } catch (Assertion\FailedExceptionInterface $exF) {
            $case->afterTest();
            $result = $this->_processTestResult(
                    $test, // The test
                    Test\ResultInterface::FAILURE, // Test status
                    $exF, // Message
                    $case, // Test case
                    $countAssertions // Assertion count
                        ? $assertionMaker->getAssertionTotalCount() - $assertionCount
                        : 0,
                    microtime(true) - $timeBeforeTest, // Time taken
                    memory_get_usage() - $memoryBeforeTest // Memory taken
            );
            $this->_afterTest($result);

            return $result;
        } catch (\Exception $exE) {
            $case->afterTest();
            $result = $this->_processTestResult(
                    $test, // The test
                    Test\ResultInterface::ERROR, // Test status
                    $exE, // Message
                    $case,
                    $countAssertions // Assertion count
                        ? $assertionMaker->getAssertionTotalCount() - $assertionCount
                        : 0,
                    microtime(true) - $timeBeforeTest, // Time taken
                    memory_get_usage() - $memoryBeforeTest // Memory taken
            );
            $this->_afterTest($result);

            return $result;
        }

        $case->afterTest();
        $result = $this->_processTestResult(
                $test, // The test
                Test\ResultInterface::SUCCESS, // Test status
                '', // Message
                $case, // Test case
                $countAssertions // Assertion count
                    ? $assertionMaker->getAssertionTotalCount() - $assertionCount
                    : 0,
                microtime(true) - $timeBeforeTest, // Time taken
                memory_get_usage() - $memoryBeforeTest // Memory taken
        );
        $this->_afterTest($result);

        return $result;
    }

    /**
     * Processes test result values.
     *
     * Updates statistics, assigns statuses, etc.
     *
     * @since [*next-version*]
     * @param Test\TestBaseInterface $test The test, the result of which to process.
     * @param string $status The status of the test.
     * @param mixed $message The message of the test.
     * @param int $assertionCount The number of assertions made in the test.
     * @param float $time The time, in seconds, that was taken to run the test.
     * @param int $memory The memory, in bytes, that was taken to run the test.
     * @return Test\ResultInterface The status of the test.
     */
    protected function _processTestResult(Test\TestBaseInterface $test, $status, $message, $assertionCount, $time, $memory)
    {
        $result = $this->_createResultFromTest(
                $test,
                $message,
                $status,
                $assertionCount,
                $this->getCode(),
                $time,
                $memory);

        return $result;
    }

    /**
     * Creates an instance of a test result using a test instance as base.
     *
     * @param Test\TestBaseInterface $test The test, from which to create a result object.
     * @param mixed $message The message of the test result.
     * @param string $status The status code of the test result.
     * @param int $assertionCount The number of assertions in the test.
     * @param string $runnerCode The code name of the runner, which ran the test.
     * @param float $time The time, in seconds, that was taken to run the test.
     * @param int $memory The memory, in bytes, that was taken to run the test.
     * @since [*next-version*]
     * @return Test\ResultInterface
     */
    protected function _createResultFromTest(Test\TestBaseInterface $test, $message, $status, $assertionCount, $runnerCode, $time, $memory)
    {
        return new Test\DefaultResult(
                $test->getCaseName(),
                $test->getMethodName(),
                $test->getKey(),
                $message,
                $status,
                $assertionCount,
                $test->getSuiteCode(),
                $runnerCode,
                $time,
                $memory);
    }

    /**
     * Runs right before a test is run.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test that is about to be run.
     * @return AbstractRunner This instance.
     */
    protected function _beforeTest(Test\TestBaseInterface $test)
    {
        ob_start();
        $this->getWriter()->writeH4(sprintf('Running Test %1$s', $test->getKey()), Writer\WriterInterface::LVL_2);

        return $this;
    }

    /**
     * Runs right after a test is run.
     *
     * @since [*next-version*]
     * @param Test\ResultInterface $result The result of the test that was ran.
     */
    protected function _afterTest(Test\ResultInterface $result)
    {
        $status = $result->getStatus();
        $writeLevel = $result->isSuccessful()
            ? Writer\WriterInterface::LVL_2
            : Writer\WriterInterface::LVL_1;
        $writer = $this->getWriter();
        $writer->writeLine($this->_getTestMessageText($result), $writeLevel);
        $writer->writeH5(sprintf('%1$s', $result->getAssertionCount()), Writer\WriterInterface::LVL_2);
        ob_end_flush();
    }

    /**
     * Retrieves a normalized text message of a test result.
     *
     * @since [*next-version*]
     * @param Test\ResultInterface $test The test result, for which to get the message.
     * @return string The normalized text of a test message.
     */
    protected function _getTestMessageText(Test\ResultInterface $test)
    {
        $message = $test->getMessage();
        if ($message instanceof \Exception) {
            if ($test->isSuccessful()) {
                return '';
            }

            if ($test->getStatus() === Test\ResultInterface::FAILURE) {
                return sprintf('Test %2$s failed:' . PHP_EOL . '%1$s' . PHP_EOL, $message->getMessage(), $test->getKey());
            }

            if ($test->getStatus() === Test\ResultInterface::ERROR) {
                return sprintf('Test %2$s erred:' . PHP_EOL . '%1$s' . PHP_EOL, (string) $message, $test->getKey());
            }
        }

        return (string) $message . PHP_EOL;
    }
}
