<?php

namespace Dhii\SimpleTest\FuncTest\Tester;

/**
 * Testing {@see Dhii\SimpleTest\Tester\AbstractStatefulTester}.
 *
 * @since [*next-version*]
 */
class AbstractStatefulTesterTest extends \Xpmock\TestCase
{
    /**
     * Creates a new stats aggreagator.
     *
     * @since [*next-version*]
     * @return Dhii\SimpleTest\Test\AbstractAggregator The new stats aggregator.
     */
    public function createStatsAggregator()
    {
        $mock = $this->mock('Dhii\\SimpleTest\\Test\\AbstractAggregator')
                ->new();

        return $mock;
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
            \Dhii\SimpleTest\Coordinator\CoordinatorInterface $coordinator,
            \Dhii\SimpleTest\Assertion\MakerInterface $assertionMaker,
            \Dhii\Stats\AggregatorInterface $aggregator)
    {
        $me = $this;
        $mock = $this->mock('Dhii\\SimpleTest\\Runner\\AbstractRunner')
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
        $reflection = $this->reflect($mock);
        $reflection->_setStatAggregator($aggregator);
        $reflection->_setCoordinator($coordinator);
        $reflection->_setAssertionMaker($assertionMaker);

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
        $mock = $this->mock('Dhii\\SimpleTest\\Suite\\AbstractSuite')
                ->new();
        $reflection = $this->reflect($mock);
        $reflection->_setCode(uniqid('testsuite-'));
        $reflection->_setCoordinator($coordinator);
        $reflection->addTests($tests);

        return $mock;
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
        $mock = $this->mock('Dhii\\SimpleTest\\Test\\AbstractTest')
                ->new();
        $reflection = $this->reflect($mock);
        $reflection->_setKey(uniqid('testkey-'));
        $reflection->_setCaseName($class);
        $reflection->_setMethodName($method);

        return $mock;
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
                ->new();
        $reflection = $this->reflect($mock);
        $reflection->_setCaseName($test->getCaseName());
        $reflection->_setMethodName($test->getMethodName());
        $reflection->_setKey($test->getKey());
        $reflection->_setMessage($message);
        $reflection->_setStatus($status);
        $reflection->_setAssertionCount($assertionCount);
        $reflection->_setRunnerCode($runnerCode);
        $reflection->_setTimeTaken($time);
        $reflection->_setMemoryTaken($memory);
        $reflection->_setSuiteCode($test->getSuiteCode());

        return $mock;
    }

    /**
     * Creates a new locator result set.
     *
     * @since [*next-version*]
     * @param \Dhii\SimpleTest\Test\TestInterface[] $results The result set.
     * @return \Dhii\SimpleTest\Locator\AbstractResultSet The new locator result set.
     */
    public function createLocatorResultSet($results)
    {
        $mock = $this->mock('Dhii\SimpleTest\Locator\AbstractResultSet')
                ->new();
        $reflection = $this->reflect($mock);
        $reflection->_addItems($results);

        return $mock;
    }

    /**
     * Create a new class locator.
     *
     * @since [*next-version*]
     * @param string $className Name of the class, for which to create the locator.
     * @return \Dhii\SimpleTest\Locator\AbstractClassLocator The new class locator.
     */
    public function createClassLocator($className)
    {
        $me = $this;
        $mock = $this->mock('Dhii\SimpleTest\Locator\AbstractClassLocator')
                ->_matchMethod(function($method) {
                    return strpos($method->getName(), 'test') === 0;
                })
                ->_createTest(function($className, $methodName, $key) use ($me) {
                    return $me->createTest($className, $methodName);
                })
                ->_createResultSet(function($results) use ($me) {
                    return $me->createLocatorResultSet($results);
                })
                ->new();
        $mock->setClass($className);

        return $mock;
    }

    /**
     * Creates a new file path locator.
     *
     * @since [*next-version*]
     * @return \Dhii\SimpleTest\Locator\AbstractFilePathLocator The new file locator instance.
     */
    public function createFileLocator()
    {
        $me = $this;
        $mock = $this->mock('Dhii\SimpleTest\Locator\AbstractFilePathLocator')
                ->_createClassLocator(function($className) use ($me) {
                    return $me->createClassLocator($className);
                })
                ->_createResultSet(function($results) use ($me) {
                    return $me->createLocatorResultSet($results);
                })
                ->_matchFile(function($fileName) {
                    $fileName = basename($fileName, '.php');
                    $suffix = 'Test';
                    // Length of $suffix from the end
                    $offset = strlen($fileName) - strlen($suffix);
                    // Position in whole filename, not from offset
                    $position = strpos($fileName, $suffix, $offset);
                    // Is the string found, and at the very end?
                    return $position === $offset;
                })
                ->new();

        return $mock;
    }

    /**
     * Creates a new writer.
     *
     * @since [*next-version*]
     *
     * @return \Dhii\SimpleTest\Writer\WriterInterface The new writer instance.
     */
    public function createWriter()
    {
        $mock = $this->mock('Dhii\SimpleTest\Writer\AbstractWriter')
                ->_write()
                ->new();

        return $mock;
    }

    /**
     * Creates a new test subject.
     *
     * @since [*next-version*]
     * @return \Dhii\SimpleTest\Tester\AbstractStatefulTester
     */
    public function createInstance()
    {
        $aggregator = $this->createStatsAggregator();
        $me = $this;
        $mock = $this->mock('Dhii\\SimpleTest\\Tester\\AbstractStatefulTester')
            ->_prepareResults(function($results) use ($me, $aggregator) {
                return $me->createResultSetIterator($results, $aggregator);
            })
            ->_createCoordinator(function() use ($me) {
                return $me->createCoordinator();
            })
            ->_createRunner(function($coordinator, $assertionMaker, $aggregator) use ($me) {
                return $me->createRunner($coordinator, $assertionMaker, $aggregator);
            })
            ->_createWriter(function() use ($me) {
                return $me->createWriter();
            })
            ->_createStatAggregator(function() use ($me) {
                return $me->createStatsAggregator();
            })
            ->_createAssertionMaker(function() use ($me) {
                return $me->createAssertionMaker();
            })
            ->new();

        return $mock;
    }

    /**
     * Tests whether a valid isntance of the test subject can be created.
     *
     * @since [*next-version*]
     */
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
    public function testRunAll($subject = null)
    {
        if (is_null($subject)) {
            $subject = $this->createInstance();
        }

        $path = (dirname(dirname(__DIR__)) . '/stub/More/MyTestCase1Test.php');
        $tests = $this->createFileLocator()->addPath($path)->locate();
        $subject->addSuite($this->createSuite($tests, $this->createCoordinator()));
        $result = $subject->runAll();

        $this->assertInstanceOf('OuterIterator', $result, 'Run result is not an outer iterator, and cannot iterate over individual result instances');
        $this->assertInstanceOf('Dhii\SimpleTest\Test\ResultSetInterface', $result, 'Run result is not a valid result set');
        $this->assertInstanceOf('Dhii\SimpleTest\Test\AccountableInterface', $result, 'Run result is not accountable for test amount');
        $this->assertInstanceOf('Dhii\SimpleTest\Test\UsageAccountableInterface', $result, 'Run result is not accountable for test usage');

        foreach ($result as $_result) {
            /* @var $_result Dhii\SimpleTest\Test\ResultInterface */
            $this->assertInstanceOf('Dhii\SimpleTest\Test\ResultInterface', $_result, 'Looping over tester result does not yield result instances');
            break;
        }

        foreach ($result->getArrayIterator() as $_resultSet) {
            /* @var $_result Dhii\SimpleTest\Test\ResultSetInterface */
            $this->assertInstanceOf('Dhii\SimpleTest\Test\ResultSetInterface', $_resultSet, 'Tester result does not expose individual result sets');
            break;
        }

        $this->assertEquals(4, $result->getTestCount(), 'Wrong result count reported');
        $this->assertEquals(1, $result->getTestCountByStatus(\Dhii\SimpleTest\Test\AccountableInterface::TEST_ERROR), 'Wrong erred result count reported');
        $this->assertEquals(1, $result->getTestCountByStatus(\Dhii\SimpleTest\Test\AccountableInterface::TEST_FAILURE), 'Wrong failed result count reported');
        $this->assertEquals(2, $result->getTestCountByStatus(\Dhii\SimpleTest\Test\AccountableInterface::TEST_SUCCESS), 'Wrong successful result count reported');
        $this->assertEquals(3, $result->getAssertionCount(), 'Wrong assertion count reported');
        $this->assertInternalType('float', $result->getTimeTaken(), 'Time reporting is incorrect');
        $this->assertGreaterThan(0, $result->getTimeTaken(), 'Wrong time taken reported');
        $this->assertInternalType('int', $result->getMemoryTaken(), 'Memory reporting is incorrect');
        $this->assertGreaterThan(0, $result->getMemoryTaken(), 'Wrong memory taken reported');
    }
}
