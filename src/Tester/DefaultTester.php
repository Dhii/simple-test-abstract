<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Writer;

/**
 * A default tester implementation.
 *
 * @since [*next-version*]
 */
class DefaultTester extends AbstractAccountableTester
{
    /**
     * @since [*next-version*]
     * @param Writer\WriterInterface $writer A writer that will be used by this tester to output data.
     */
    public function __construct(Writer\WriterInterface $writer)
    {
        $this->_setWriter($writer);
    }

    /**
     * Executed after running all test suites.
     *
     * Generates a very basic report of the testing summary.
     *
     * @since [*next-version*]
     * @return DefaultTester This instance.
     */
    protected function _afterRunAll()
    {
        parent::_afterRunAll();
        $writer = $this->getWriter();

        if (!$this->getTestCount()) {
            $writer->writeLine('No tests were ran');
            return $this;
        }

        if (!$this->getUnsuccessfulTestCount()) {
            $writer->writeLine('OK!');
            return $this;
        }

        $writer->writeLine('PROBLEMS!');
        $writer->writeLine(sprintf('%1$d failed, %2$d erred, %3$d successful',
                $this->getTestCountByStatus(self::TEST_FAILURE),
                $this->getTestCountByStatus(self::TEST_ERROR),
                $this->getTestCountByStatus(self::TEST_SUCCESS)
        ));
    }

    /**
     * Retrieve the total number of unsuccessfult tests in all test suites.
     *
     * @since [*next-version*]
     * @return int The number of unsuccessfult tests.
     */
    public function getUnsuccessfulTestCount()
    {
        return $this->getTestCountByStatus(self::TEST_FAILURE)
                + $this->getTestCountByStatus(self::TEST_ERROR);
    }

    /**
     * Retrieve the total number of successfult tests in all test suites.
     *
     * @since [*next-version*]
     * @return int The number of successfult tests.
     */
    public function getSuccessfulTestCount()
    {
        return intval($this->getTestCountByStatus(self::TEST_SUCCESS));
    }
}
