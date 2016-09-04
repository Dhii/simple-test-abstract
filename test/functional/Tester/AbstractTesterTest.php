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
        $reflection->items = $items;

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
        return $this->mock('Dhii\\SimpleTest\\Runner\\AbstractRunner')
                ->_getStatAggregator($aggregator)
                ->_getCoordinator($coordinator)
                ->_getAssertionMaker($assertionMaker)
                ->getCode(uniqid('testrunner-'))
                ->new();
    }

    /**
     * Creates a new assertion maker.
     *
     * @since [*next-version*]
     * @return Dhii\SimpleTest\Assertion\AbstractMaker
     */
    public function createAssertionMaker()
    {
        return $this->mock('Dhii\\SimpleTest\\Assertion\\AbstractMaker')
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
            $this->createTest('Dhii\\SimpleTest\\Test\\Stub\\MyTestCaseTest', 'testSuccess')
        );
        $subject->addSuite($this->createSuite($tests, $this->createCoordinator()));
        $subject->runAll();
    }
}
