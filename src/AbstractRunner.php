<?php

namespace Dhii\SimpleTest;

use Dhii\SimpleTest\Writer;

/**
 * Common functionality for test runners.
 *
 * @since [*next-version*]
 */
abstract class AbstractRunner extends Test\AbstractSupervisor implements RunnerInterface
{
    protected $writer;
    protected $assertionMaker;

    /**
     * @since [*next-version*]
     * @param Writer\WriterInterface $writer The writer that this runner will use to output data.
     * @param Assertion\DefaultMaker $assertionMaker The assertion maker that test cases run by this runner will use.
     */
    public function __construct(Writer\WriterInterface $writer, Assertion\DefaultMaker $assertionMaker)
    {
        $this->_setWriter($writer);
        $this->_setAssertionMaker($assertionMaker);
    }

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
    public function getWriter() {
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
        $assertionCount = $assertionMaker->getAssertionTotalCount();
        $assertionStatusCount = $assertionMaker->getAssertionStatusCount();

        try {
            $className = $test->getCaseName();
            $methodName = $test->getMethodName();
            $case = new $className($this->_getAssertionMaker());
            $this->_beforeTest($test, $case);
            $case->{$methodName}();
        } catch (Assertion\FailedExceptionInterface $exF) {
            return $this->_processTestResult(
                    $test,
                    Test\ResultInterface::FAILURE,
                    $exF,
                    $case,
                    $assertionMaker->getAssertionTotalCount() - $assertionCount,
                    $assertionStatusCount,
                    $assertionMaker->getAssertionStatusCount());

        } catch (\Exception $exE) {
            return $this->_processTestResult(
                    $test,
                    Test\ResultInterface::ERROR,
                    $exE,
                    $case,
                    $assertionMaker->getAssertionTotalCount() - $assertionCount,
                    $assertionStatusCount,
                    $assertionMaker->getAssertionStatusCount());
        }

        return $this->_processTestResult(
                $test,
                Test\ResultInterface::SUCCESS,
                '',
                $case,
                $assertionMaker->getAssertionTotalCount() - $assertionCount,
                $assertionStatusCount,
                $assertionMaker->getAssertionStatusCount());
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
     * @param CaseInterface $case The test case, to which the test belonged.
     * @param int $assertionCount The number of assertions made in the test.
     * @param int $oldAssertionStatusCount The total number of assertions made before the test.
     * @param int $newAssertionStatusCount The total number of assertions made after the test.
     * @return Test\ResultInterface The status of the test.
     */
    protected function _processTestResult(Test\TestBaseInterface $test, $status, $message, CaseInterface $case, $assertionCount, $oldAssertionStatusCount, $newAssertionStatusCount)
    {
        $result = $this->_createResultFromTest(
                $test,
                $message,
                $status,
                $assertionCount,
                $this->getCode());

        $this->_addStatusCount($result->getStatus());
        $this->_updateAssertionStatusCounts($oldAssertionStatusCount, $newAssertionStatusCount);

        $this->_afterTest($result, $case);

        return $status;
    }

    /**
     * Creates an instance of a test result using a test instance as base.
     *
     * @param Test\TestBaseInterface $test The test, from which to create a result object.
     * @param mixed $message The message of the test result.
     * @param string $status The status code of the test result.
     * @param int $assertionCount The number of assertions in the test.
     * @param string $runnerCode The code name of the runner, which ran the test.
     * @since [*next-version*]
     * @return Test\ResultInterface
     */
    protected function _createResultFromTest(Test\TestBaseInterface $test, $message, $status, $assertionCount, $runnerCode)
    {
        return new Test\DefaultResult(
                $test->getCaseName(),
                $test->getMethodName(),
                $test->getKey(),
                $message,
                $status,
                $assertionCount,
                $test->getSuiteCode(),
                $runnerCode);
    }

    /**
     * Runs right before a test is run.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test that is about to be run.
     * @param CaseInterface $case The test case that the test belongs to.
     */
    protected function _beforeTest(Test\TestBaseInterface $test, CaseInterface $case)
    {
        ob_start();
        $this->getWriter()->writeH4(sprintf('Running Test %1$s', $test->getKey()), Writer\WriterInterface::LVL_2);
        $case->beforeTest();
    }

    /**
     * Runs right after a test is run.
     *
     * @since [*next-version*]
     * @param Test\ResultInterface $result The result of the test that was ran.
     * @param CaseInterface $case The test case that the test belongs to.
     */
    protected function _afterTest(Test\ResultInterface $result, CaseInterface $case)
    {
        $case->afterTest();
        $status = $result->getStatus();
        $writeLevel = $result->isSuccessful()
            ? Writer\WriterInterface::LVL_2
            : Writer\WriterInterface::LVL_1;
        $writer = $this->getWriter();
        $writer->writeLine($this->_getTestMessageText($result), $writeLevel);
        $writer->writeH5(sprintf('%2$d / %1$s', $this->getTestStatusMessage($status), $result->getAssertionCount()), Writer\WriterInterface::LVL_2);
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
