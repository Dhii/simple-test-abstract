<?php

namespace Dhii\SimpleTest\FuncTest\Tester;

/**
 * Tests {@see \Dhii\SimpleTest\Test\AbstractResultSet}.
 *
 * @since 0.1.0
 */
class AbstractResultSetTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1.0
     *
     * @return \Dhii\SimpleTest\Test\AbstractResultSet The new subject instance.
     */
    public function createInstance($items = array())
    {
        $mock = $this->mock('Dhii\\SimpleTest\\Test\\AbstractResultSet')
                ->new();

        $reflection = $this->reflect($mock);
        $reflection->_setStatAggregator($this->createStatAggregator());
        $reflection->_setItems($items);

        return $mock;
    }

    /**
     * Creates a stat aggregator.
     *
     * @since 0.1.0
     *
     * @return \Dhii\Stats\AbstractAggregator The new stat aggregator instance.
     */
    public function createStatAggregator()
    {
        $mock = $this->mock('Dhii\\Stats\\AbstractAggregator')
                ->new();

        return $mock;
    }

    /**
     * Tests that a valid result set can be created.
     *
     * @since 0.1.0
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf('Dhii\SimpleTest\Test\ResultSetInterface', $subject, 'Subject is not a valid result set');
    }

    /**
     * Tests that totals can be retrieved without error even if no test results recorded.
     *
     * @since 0.1.0
     */
    public function testRetrieveTotalsEmpty()
    {
        $subject = $this->createInstance();

        $this->assertNull($subject->getAssertionCount(), 'Incorrect assertion count');
        $this->assertNull($subject->getMemoryTaken(), 'Incorrect memory amount');
        $this->assertNull($subject->getTimeTaken(), 'Incorrect time amount');
        $this->assertNull($subject->getTestCount(), 'Incorrect test count');
    }
}
