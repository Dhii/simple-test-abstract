<?php

namespace Dhii\SimpleTest\Tester;

use Dhii\SimpleTest\Writer;
use Dhii\SimpleTest\Coordinator;
use Dhii\SimpleTest\Assertion;
use Dhii\SimpleTest\Suite;
use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Runner;
use Dhii\Stats;

/**
 * Common functionality for testers that are able to generate their own components.
 *
 * @since [*next-version*]
 */
abstract class AbstractStatefulTester extends AbstractTester
{
    /** @var Writer\WriterInterface */
    protected $writer;
    /** @var Stats\AggregatorInterface */
    protected $statAggregator;
    /** @var Assertion\MakerInterface */
    protected $assertionMaker;
    /** @var Coordinator\CoordinatorInterface */
    protected $coordinator;
    /** @var Runner\RunnerInterface */
    protected $runner;
    /** @var Suite\SuiteInterface[] */
    protected $suites = array();

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     *
     * @return AbstractTester This instance.
     */
    public function addSuite(Suite\SuiteInterface $suite)
    {
        $this->_getCoordinatorInstance()->beforeAddSuite($suite, $this);
        $this->suites[$suite->getCode()] = $suite;
        $this->_getCoordinatorInstance()->afterAddSuite($suite, $this);

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getSuites()
    {
        return $this->suites;
    }

    /**
     * Sets the coordinator to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Coordinator\CoordinatorInterface $coordinator The coordinator to set.
     *
     * @return AbstractTester This instance.
     */
    protected function _setCoordinator(Coordinator\CoordinatorInterface $coordinator)
    {
        $this->coordinator = $coordinator;

        return $this;
    }

    /**
     * Retrieve the coordinator that is used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Coordinator\CoordinatorInterface The coordinator used by this instance.
     */
    protected function _getCoordinator()
    {
        return $this->coordinator;
    }

    /**
     * Retrieve the coordinator used by this instance.
     *
     * If coordinator not assigned, creates a new coordinator.
     *
     * @since [*next-version*]
     *
     * @return Coordinator\CoordinatorInterface The coordinator used by this instance.
     */
    protected function _getCoordinatorInstance()
    {
        if (is_null($this->_getCoordinator())) {
            $this->_setCoordinator($this->_createCoordinator($this->_getWriterInstance()));
        }

        return $this->_getCoordinator();
    }

    /**
     * Creates a new coordinator.
     *
     * @since [*next-version*]
     *
     * @param Writer\WriterInterface The writer that the new corrdinator will use.
     *
     * @return Coordinator\DefaultCoordinator The new coordinator.
     */
    abstract protected function _createCoordinator(Writer\WriterInterface $writer);

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _getRunnerInstance()
    {
        if (is_null($this->_getRunner())) {
            $this->_setRunner($this->_createRunner(
                    $this->_getCoordinatorInstance(),
                    $this->_getAssertionMakerInstance(),
                    $this->_getStatAggregatorInstance()));
        }

        return $this->_getRunner();
    }

    /**
     * Create a new runner.
     *
     * @since [*next-version*]
     *
     * @param Coordinator\CoordinatorInterface $coordinator    The coordinator that the runner will use.
     * @param Assertion\MakerInterface         $assertionMaker The assertion maker that the runner will use.
     * @param Stats\AggregatorInterface        $statAggregator The stat aggregator that the runner will use.
     *
     * @return Runner\DefaultRunner The new runner.
     */
    abstract protected function _createRunner(
            Coordinator\CoordinatorInterface $coordinator,
            Assertion\MakerInterface $assertionMaker,
            Stats\AggregatorInterface $statAggregator);

    /**
     * Retrieve the runner used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Runner\RunnerInterface The runner used by this instance.
     */
    protected function _getRunner()
    {
        return $this->runner;
    }

    /**
     * Set the runner to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Runner\RunnerInterface $runner The runner to be used by this instance.
     */
    protected function _setRunner(Runner\RunnerInterface $runner)
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * Retrieve the writer that this instance uses.
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
     * Assign the writer for this instance to use.
     *
     * @since [*next-version*]
     *
     * @param Writer\WriterInterface $writer The writer for this instance to use.
     *
     * @return AbstractGeneratingTester This instance.
     */
    protected function _setWriter(\Dhii\SimpleTest\Writer\WriterInterface $writer)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Retrieve the writer that this instance uses.
     *
     * If no writer assigned, creates a new default writer.
     *
     * @since [*next-version*]
     *
     * @return Writer\WriterInterface The writer used by this instance.
     */
    protected function _getWriterInstance()
    {
        if (is_null($this->_getWriter())) {
            $this->_setWriter($this->_createWriter());
        }

        return $this->_getWriter();
    }

    /**
     * Creates a new writer.
     *
     * @since [*next-version*]
     *
     * @return Writer\WriterInterface The new writer.
     */
    abstract protected function _createWriter();

    /**
     * Assign the stat aggregator to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Stats\AggregatorInterface $statAggregator The stat aggregator that will be assigned to test results.
     *
     * @return AbstractGeneratingTester This instance.
     */
    protected function _setStatAggregator(Stats\AggregatorInterface $statAggregator)
    {
        $this->statAggregator = $statAggregator;

        return $this;
    }

    /**
     * Retrieve the stat aggregator used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Stats\AggregatorInterface The stat aggregator that is used by this instance.
     */
    protected function _getStatAggregator()
    {
        return $this->statAggregator;
    }

    /**
     * Retrieve the stat aggregator used by this instance.
     *
     * If no stat aggregator assigned, creates a new default aggregator.
     *
     * @since [*next-version*]
     *
     * @return Stats\AggregatorInterface The stat aggregator that is used by this instance.
     */
    protected function _getStatAggregatorInstance()
    {
        if (is_null($this->_getStatAggregator())) {
            $this->_setStatAggregator($this->_createStatAggregator());
        }

        return $this->_getStatAggregator();
    }

    /**
     * Create a new stat aggregator.
     *
     * @since [*next-version*]
     *
     * @return Stats\AggregatorInterface The new stat aggregator.
     */
    abstract protected function _createStatAggregator();

    /**
     * Retrieve the assertion maker used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Assertion\MakerInterface The assertion maker used by this instance.
     */
    protected function _getAssertionMaker()
    {
        return $this->assertionMaker;
    }

    /**
     * Assign the assertion maker to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param Assertion\MakerInterface $assertionMaker The assertion maker to be used by this instance.
     *
     * @return AbstractGeneratingTester This instance.
     */
    protected function _setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;

        return $this;
    }

    /**
     * Retrieve the assertion maker used by this instance.
     *
     * @since [*next-version*]
     *
     * @return Assertion\MakerInterface The assertion maker used by this instance.
     */
    protected function _getAssertionMakerInstance()
    {
        if (is_null($this->_getAssertionMaker())) {
            $this->_setAssertionMaker($this->_createAssertionMaker());
        }

        return $this->_getAssertionMaker();
    }

    /**
     * Creates a new assertion maker.
     *
     * @since [*next-version*]
     *
     * @return Assertion\MakerInterface The new assertion maker.
     */
    abstract protected function _createAssertionMaker();
}
