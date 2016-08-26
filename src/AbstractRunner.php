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
    public function run(Test\TestInterface $test)
    {
        return $this->_run($test);
    }

    /**
     * Low-level running of a test.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test to run.
     */
    protected function _run(Test\TestInterface $test)
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
                    Test\TestInterface::FAILURE,
                    $exF,
                    $case,
                    $assertionMaker->getAssertionTotalCount() - $assertionCount,
                    $assertionStatusCount,
                    $assertionMaker->getAssertionStatusCount());

        } catch (\Exception $exE) {
            return $this->_processTestResult(
                    $test,
                    Test\TestInterface::ERROR,
                    $exE,
                    $case,
                    $assertionMaker->getAssertionTotalCount() - $assertionCount,
                    $assertionStatusCount,
                    $assertionMaker->getAssertionStatusCount());
        }

        return $this->_processTestResult(
                $test,
                Test\TestInterface::SUCCESS,
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
     * @param Test\TestInterface $test The test, the result of which to process.
     * @param string $status The status of the test.
     * @param mixed $message The message of the test.
     * @param CaseInterface $case The test case, to which the test belonged.
     * @param int $assertionCount The number of assertions made in the test.
     * @param int $oldAssertionStatusCount The total number of assertions made before the test.
     * @param int $newAssertionStatusCount The total number of assertions made after the test.
     * @return string The status of the test.
     */
    protected function _processTestResult(Test\TestInterface $test, $status, $message, CaseInterface $case, $assertionCount, $oldAssertionStatusCount, $newAssertionStatusCount)
    {
        $test->setStatus($status);
        if (!$test->isSuccessful()) {
            $test->setMessage($message);
        }

        $test->setAssertionCount($assertionCount);
        $this->_addStatusCount($test->getStatus());
        $this->_updateAssertionStatusCounts($oldAssertionStatusCount, $newAssertionStatusCount);

        $this->_afterTest($test, $case);

        return $status;
    }

    /**
     * Runs right before a test is run.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test that is about to be run.
     * @param CaseInterface $case The test case that the test belongs to.
     */
    protected function _beforeTest(Test\TestInterface $test, CaseInterface $case)
    {
        ob_start();
        $this->getWriter()->writeH4(sprintf('Running Test %1$s', $test->getKey()), Writer\WriterInterface::LVL_2);
        $case->beforeTest();
    }

    /**
     * Runs right after a test is run.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test that was ran.
     * @param CaseInterface $case The test case that the test belongs to.
     */
    protected function _afterTest(Test\TestInterface $test, CaseInterface $case)
    {
        $case->afterTest();
        $status = $test->getStatus();
        $writeLevel = $test->isSuccessful()
            ? Writer\WriterInterface::LVL_2
            : Writer\WriterInterface::LVL_1;
        $writer = $this->getWriter();
        $writer->writeLine($this->_getTestMessageText($test), $writeLevel);
        $writer->writeH5(sprintf('%2$d / %1$s', $this->getTestStatusMessage($status), $test->getAssertionCount()), Writer\WriterInterface::LVL_2);
        ob_end_flush();
    }

    /**
     * Retrieves a normalized text message of a run test.
     *
     * @since [*next-version*]
     * @param Test\TestInterface $test The test, for which to get the message.
     * @return string The normalized text of a test message.
     */
    protected function _getTestMessageText(Test\TestInterface $test)
    {
        $message = $test->getMessage();
        if ($message instanceof \Exception) {
            if ($test->isSuccessful()) {
                return '';
            }

            if ($test->getStatus() === Test\TestInterface::FAILURE) {
                return sprintf('Test %2$s failed:' . PHP_EOL . '%1$s' . PHP_EOL, $message->getMessage(), $test->getKey());
            }

            if ($test->getStatus() === Test\TestInterface::ERROR) {
                return sprintf('Test %2$s erred:' . PHP_EOL . '%1$s' . PHP_EOL, (string) $message, $test->getKey());
            }
        }

        return (string) $message . PHP_EOL;
    }
}
