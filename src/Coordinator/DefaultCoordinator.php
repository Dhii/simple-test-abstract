<?php

namespace Dhii\SimpleTest\Coordinator;

use Dhii\SimpleTest\Writer;
use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Tester;
use Dhii\SimpleTest\Assertion;

/**
 * A default handling implementation.
 *
 * @since [*next-version*]
 */
class DefaultCoordinator extends AbstractCoordinator
{
    protected $writer;

    /**
     * @param Writer\WriterInterface $writer The writer to be used by this instance.
     */
    public function __construct(Writer\WriterInterface $writer)
    {
        $this->_setWriter($writer);
    }

    /**
     * Retrieve the writer used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Writer\WriterInterface The writer used by this instance.
     */
    protected function _getWriter()
    {
        return $this->writer;
    }

    /**
     * Sets a writer to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Writer\WriterInterface $writer The writer to be used by this instance.
     *
     * @return DefaultCoordinator This instance.
     */
    protected function _setWriter(Writer\WriterInterface $writer)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Retrieves a normalized text message of a test result.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface $test The test result, for which to get the message.
     *
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

    /**
     * Format a size in human-readable units.
     *
     * @since [*next-version*]
     *
     * @param int $size      A size in bytes.
     * @param int $precision To which digit to round the eventual result.
     *
     * @return string The size, in a human-readable format, with units appended.
     */
    protected function _humanSize($size, $precision = 2)
    {
        $units = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $step  = 1024;
        $i     = 0;
        while (($size / $step) > 0.9) {
            $size = $size / $step;
            ++$i;
        }

        return round($size, $precision) . $units[$i];
    }

    /**
     * Executes before a test runs.
     *
     * @since [*next-version*]
     *
     * @param Test\TestInterface $test   The test that is about to run.
     * @param mixed              $source The source of this event.
     */
    public function beforeRunTest(Test\TestInterface $test, $source = null)
    {
        $this->_getWriter()->writeH4(sprintf('Running Test %1$s', $test->getKey()), Writer\WriterInterface::LVL_2);
    }

    /**
     * Executes after a test runs.
     *
     * @since [*next-version*]
     *
     * @param Test\ResultInterface $result The result of the test that was ran.
     * @param mixed                $source The source of this event.
     */
    public function afterRunTest(Test\ResultInterface $result, $source = null)
    {
        $writeLevel = $result->isSuccessful()
            ? Writer\WriterInterface::LVL_2
            : Writer\WriterInterface::LVL_1;
        $writer = $this->_getWriter();
        $writer->writeLine($this->_getTestMessageText($result), $writeLevel);
        $summary = sprintf('Assertions: %1$d', $result->getAssertionCount());
        if ($result instanceof Test\UsageAccountableInterface) {
            $summary .= sprintf(', Memory: %1$dB, Time: %2$.7Fs', $result->getMemoryTaken(), $result->getTimeTaken());
        }

        $writer->writeH5($summary, Writer\WriterInterface::LVL_2);
    }

    /**
     * Executes before a tester runs all of its suites.
     *
     * @since [*next-version*]
     *
     * @param Tester\ResultSetInterface $results The tester that is about to run the suites.
     * @param mixed                  $source The source of this event.
     */
    public function afterRunAllSuites(Test\ResultSetInterface $results, $source = null)
    {
        $writer = $this->_getWriter();

        // If we can't know what happened, finish
        if (!($results instanceof Test\AccountableInterface)) {
            $writer->writeLine('Finished');

            return $this;
        }

        // Notify if nothing to test
        if (!$results->getTestCount()) {
            $writer->writeLine('No tests were ran');

            return $this;
        }

        $unsuccessfulTestCount = $results->getTestCountByStatus(Test\AccountableInterface::TEST_FAILURE)
                + $results->getTestCountByStatus(Test\AccountableInterface::TEST_ERROR);

        if (!$unsuccessfulTestCount) {
            $writer->writeH4('OK!');

            return $this;
        }

        $writer->writeH4('PROBLEMS!');
        $totalTestCount  = $results->getTestCount();
        $failedTestCount = $results->getTestCountByStatus(Test\AccountableInterface::TEST_FAILURE);
        $erredTestCount  = $results->getTestCountByStatus(Test\AccountableInterface::TEST_ERROR);
        $passedTestCount = $results->getTestCountByStatus(Test\AccountableInterface::TEST_SUCCESS);
        $summary         = sprintf('%1$d failed (%2$d%%), %3$d erred (%4$d%%), %5$d passed (%6$d%%), %7$d total',
                $failedTestCount,
                $failedTestCount / $totalTestCount * 100,
                $erredTestCount,
                $erredTestCount / $totalTestCount * 100,
                $passedTestCount,
                $passedTestCount / $totalTestCount * 100,
                $totalTestCount);

        if ($results instanceof Assertion\AccountableInterface) {
            $summary .= sprintf(', %1$d assertions', $results->getAssertionCount());
        }

        $writer->writeLine($summary);

        if ($results instanceof Test\UsageAccountableInterface) {
            $writer->writeLine(sprintf('%1$s, %2$.3Fs',
                    $this->_humanSize($results->getMemoryTaken(), 3),
                    $results->getTimeTaken()));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _any($target, $data = null, $source = null)
    {
        parent::_any($target, $data, $source);
    }
}
