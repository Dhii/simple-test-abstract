<?php

namespace Dhii\SimpleTest\FuncTest\Tester;

/**
 * Testing {@see Dhii\SimpleTest\Tester\AbstractTester}.
 *
 * @since [*next-version*]
 */
class AbstractTesterTest extends \Xpmock\TestCase
{
    /**
     * Creates a new stats aggreagator.
     *
     * @since [*next-version*]
     * @return Dhii\SimpleTest\Test\AbstractAggregator The new stats aggregator.
     */
    public function createStatsAggregator()
    {
        return $this->mock('Dhii\\SimpleTest\\Test\\AbstractAggregator')
                ->new();
    }

    /**
     * Creates a new result set instance.
     *
     * @since [*next-version*]
     * @param \Dhii\SimpleTest\Test\ResultInterface[] $results The results to go into the set.
     * @param \Dhii\SimpleTest\Test\AbstractAggregator $statAggregator Aggregator of stats.
     * @return \Dhii\SimpleTest\Test\ResultSetInterface The new result set.
     */
    public function createResultSet($results, \Dhii\SimpleTest\Test\AbstractAggregator $statAggregator)
    {
        $me = $this;
        $mock = $this->mock('Dhii\SimpleTest\Test\AbstractResultSet')
                ->_createSearchResultsIterator(function($results) use ($me, $statAggregator) {
                    return $me->_createResultSet($results, $statAggregator);
                })
                ->_getStatAggregator($statAggregator)
                ->new();
        $this->reflect($mock)->_setItems($results);

        return $mock;
    }

    /**
     * Creates a new result set iterator, which coombines result sets.
     *
     * @since [*next-version*]
     * @param \Dhii\SimpleTest\Locator\ResultSetInterface[] The result sets for the iterator.
     * @param \Dhii\Stats\AggregatorInterface $aggregator The aggregator for use of the iterator.
     * @return \Dhii\SimpleTest\Test\AbstractResultSetCollection The new result set iterator.
     */
    public function createResultSetIterator($items, \Dhii\Stats\AggregatorInterface $aggregator)
    {
        $mock = $this->mock('Dhii\\SimpleTest\\Test\\AbstractResultSetCollection')
                ->_getStatAggregator($aggregator)
                ->_createInnerIterator(function() {
                    return new \AppendIterator();
                })
                ->_validateItem()
                ->new();

        $reflection = $this->reflect($mock);
        $reflection->_addItems($items);

        return $mock;
    }

    /**
     * Create a new coordinator instance.
     *
     * @since [*next-version*]
     * @return Dhii\SimpleTest\Coordinator\AbstractCoordinator The new coordinator.
     */
    public function createCoordinator()
    {
        return $this->mock('Dhii\\SimpleTest\\Coordinator\\AbstractCoordinator')
                ->new();
    }

    /**
     * Cretes a new runner instance.
     *
     * @since [*next-version*]
     * @return \Dhii\SimpleTest\Runner\AbstractRunner
     */
    public function createRunner(
            \Dhii\Stats\AggregatorInterface $aggregator,
            \Dhii\SimpleTest\Coordinator\CoordinatorInterface $coordinator,
            \Dhii\SimpleTest\Assertion\MakerInterface $assertionMaker)
    {
        $me = $this;
        $mock = $this->mock('Dhii\\SimpleTest\\Runner\\AbstractRunner')
                ->_getStatAggregator($aggregator)
                ->_getCoordinator($coordinator)
                ->_getAssertionMaker($assertionMaker)
                ->_createResultFromTest(function(\Dhii\SimpleTest\Test\TestBaseInterface $test, $message, $status, $assertionCount, $runnerCode, $time, $memory) use ($me) {
                    return $me->createTestResult(
                            $test,
                            $message,
                            $status,
                            $assertionCount,
                            $runnerCode,
                            $time,
                            $memory);
                })
                ->_createResultSet(function($results) use ($me, $aggregator) {
                    return $me->createResultSet($results, $aggregator);
                })
                ->getCode(uniqid('testrunner-'))
                ->new();

        return $mock;
    }

    /**
     * Creates a new assertion maker.
     *
     * @since [*next-version*]
     * @return Dhii\SimpleTest\Assertion\AbstractMaker
     */
    public function createAssertionMaker()
    {
        return $this->mock('Dhii\\SimpleTest\\Assertion\\AbstractAccountableMaker')
                ->new();
    }

    /**
     * Creates a new test suite.
     *
     * @since [*next-version*]
     * @param \Dhii\SimpleTest\Test\TestInterface[] $tests The tests for the suite.
     * @param \Dhii\SimpleTest\Coordinator\CoordinatorInterface $coordinator The coordinator for the suite.
     * @return \Dhii\SimpleTest\Suite\AbstractSuite
     */
    public function createSuite($tests, \Dhii\SimpleTest\Coordinator\CoordinatorInterface $coordinator)
    {
        return $this->mock('Dhii\\SimpleTest\\Suite\\AbstractSuite')
                ->getCode(uniqid('testsuite-'))
                ->_getCoordinator($coordinator)
                ->_getTests($tests)
                ->new();
    }

    /**
     * Creates a new test.
     *
     * @since [*next-version*]
     * @param string $class Name of the test case class.
     * @param string $method The method, which this test will run.
     * @return \Dhii\SimpleTest\Test\AbstractTest
     */
    public function createTest($class, $method)
    {
        return $this->mock('Dhii\\SimpleTest\\Test\\AbstractTest')
                ->getKey(uniqid('testkey-'))
                ->getCaseName($class)
                ->getMethodName($method)
                ->new();
    }

    /**
     *
     * @since [*next-version*]
     * @param \Dhii\SimpleTest\Test\TestBaseInterface $test
     * @param mixed $message
     * @param string $status
     * @param int $assertionCount
     * @param string $runnerCode
     * @param float $time
     * @param int $memory
     * @return \Dhii\SimpleTest\Test\AbstractAccountableResult
     */
    public function createTestResult(\Dhii\SimpleTest\Test\TestBaseInterface $test, $message, $status, $assertionCount, $runnerCode, $time, $memory)
    {
        $mock = $this->mock('Dhii\SimpleTest\Test\AbstractAccountableResult')
                ->getCaseName($test->getCaseName())
                ->getMethodName($test->getMethodName())
                ->getKey($test->getKey())
                ->getMessage($message)
                ->getStatus($status)
                ->getAssertionCount($assertionCount)
                ->getRunnerCode($runnerCode)
                ->getTimeTaken($time)
                ->getMemoryTaken($memory)
                ->getSuiteCode($test->getSuiteCode())
                ->new();

        return $mock;
    }

    /**
     * Creates a new test subject.
     *
     * @since [*next-version*]
     * @return \Dhii\SimpleTest\Tester\AbstractTester
     */
    public function createInstance()
    {
        $aggregator = $this->createStatsAggregator();
        $coordinator = $this->createCoordinator();
        $assertionMaker = $this->createAssertionMaker();
        $runner = $this->createRunner($aggregator, $coordinator, $assertionMaker);
        $me = $this;
        $mock = $this->mock('Dhii\\SimpleTest\\Tester\\AbstractTester')
            ->_createResultSetIterator(function($results) use ($me, $aggregator) {
                return $me->createResultSetIterator($results, $aggregator);
            })
            ->_getCoordinator($coordinator)
            ->_getRunner($runner)
            ->new();

        return $mock;
    }

    public function testCanBeCreated()
    {
        $subject = $this->createInstance();
        $this->assertInstanceOf('Dhii\\SimpleTest\\Tester\\TesterInterface', $subject, 'Not a valid tester');
    }

    /**
     * Tests the main functionality.
     *
     * @since [*next-version*]
     */
    public function testRunAll()
    {
        $subject = $this->createInstance();
        $reflection = $this->reflect($subject);

        $tests = array(
            $this->createTest('Dhii\\SimpleTest\\Test\\Stub\\More\\MyTestCase1Test', 'testFailure'),
            $this->createTest('Dhii\\SimpleTest\\Test\\Stub\\More\\MyTestCase1Test', 'testSuccess'),
            $this->createTest('Dhii\\SimpleTest\\Test\\Stub\\More\\MyTestCase1Test', 'testError')
        );
        $subject->addSuite($this->createSuite($tests, $this->createCoordinator()));
        $result = $subject->runAll();

        $this->assertInstanceOf('Dhii\SimpleTest\Test\ResultSetInterface', $result, 'Run result is not a valid result set');
        $this->assertInstanceOf('Dhii\SimpleTest\Test\AccountableInterface', $result, 'Run result is not accountable for test amount');
        $this->assertInstanceOf('Dhii\SimpleTest\Test\UsageAccountableInterface', $result, 'Run result is not accountable for test usage');

        $this->assertEquals(count($tests), $result->getTestCount(), 'Wrong result count reported');
        $this->assertEquals(1, $result->getTestCountByStatus(\Dhii\SimpleTest\Test\AccountableInterface::TEST_ERROR), 'Wrong erred result count reported');
        $this->assertEquals(1, $result->getTestCountByStatus(\Dhii\SimpleTest\Test\AccountableInterface::TEST_FAILURE), 'Wrong failed result count reported');
        $this->assertEquals(1, $result->getTestCountByStatus(\Dhii\SimpleTest\Test\AccountableInterface::TEST_SUCCESS), 'Wrong successful result count reported');
        $this->assertEquals(3, $result->getAssertionCount(), 'Wrong assertion count reported');
        $this->assertInternalType('float', $result->getTimeTaken(), 'Time reporting is incorrect');
        $this->assertGreaterThan(0, $result->getTimeTaken(), 'Wrong time taken reported');
        $this->assertInternalType('int', $result->getMemoryTaken(), 'Memory reporting is incorrect');
        $this->assertGreaterThan(0, $result->getMemoryTaken(), 'Wrong memory taken reported');
    }
}
