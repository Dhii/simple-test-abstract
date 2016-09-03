<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest;
use Dhii\SimpleTest\TestCase;

if (!class_exists('Dhii\\SimpleTest\\ExceptionInterface')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
}

//require_once(__DIR__.'/MyTestCaseTest1.php');
//require_once(__DIR__.'/MyTestCaseTest1.php');

class MyTestSource extends AbstractSource
{
    /**
     * Demonstrates how a test source can be fed by an array.
     *
     * @return SimpleTest\Test\DefaultTest[]
     */
    public function getItems1() {
        $testClass = 'Dhii\\SimpleTest\\Test\\MyTestCaseTest';
        $errorTest = new SimpleTest\Test\DefaultTest($testClass, 'testError', sprintf('%1$s::%2$s', $testClass, 'testError'));
        $tests = array(
            new SimpleTest\Test\DefaultTest($testClass, 'testNothing', sprintf('%1$s::%2$s', $testClass, 'testNothing')),
            new SimpleTest\Test\DefaultTest($testClass, 'testFailure', sprintf('%1$s::%2$s', $testClass, 'testFailure')),
            new SimpleTest\Test\DefaultTest($testClass, 'testSuccess', sprintf('%1$s::%2$s', $testClass, 'testSuccess')),
//            $errorTest, // This won't work, because you can't add the same test twice - to any suite
            $errorTest
        );

        return $tests;
    }

    /**
     * Demonstrates how a test source can be fed from a class locator.
     *
     * @return \Traversable
     */
    public function getItems2()
    {
        $locator = new SimpleTest\Locator\DefaultClassLocator();
        $locator->setClass('Dhii\\SimpleTest\\Test\\MyTestCaseTest');
        return $locator->locate();
    }

    /**
     * Demonstrates how a test source can be fed from a file locator.
     *
     * @return \Traversable
     */
    public function getItems()
    {
        $locator = new SimpleTest\Locator\DefaultFilePathLocator();
//        $locator->addPath(__DIR__.'/*'); // All files in a folder
//        $locator->addPath(__DIR__.'/MyTestCaseTest.php'); // A specific file
//        $locator->addPath(dirname(__DIR__).'/*/*'); // A file pattern
//        $dir = new \RecursiveDirectoryIterator(dirname(__DIR__)); // This can be any iterator, including a \RecursiveIteratorIterator
//        $locator->addPath($dir); // All files in a specific directory and subdirectories
        $locator->addPath(array(
//            __DIR__.'/MyTestCaseTest.php',
            __DIR__.'/More/MyTestCase1Test.php',
//            __DIR__.'/More/EvenMore/MyTestCase2Test.php',
        )); // A set of specific test files


        return $locator->locate();
    }
}

class BasicTestCase
{

    public function createWriter($verbosityLevel = 1)
    {
        $writer = new SimpleTest\Writer\DefaultWriter();
        $writer->setLevel($verbosityLevel);

        return $writer;
    }

    public function createAssertionMaker()
    {
        $assertionMaker = new SimpleTest\Assertion\DefaultMaker();

        return $assertionMaker;
    }

    public function createCoordinator(SimpleTest\Writer\WriterInterface $writer)
    {
        $coordinator = new SimpleTest\Coordinator\DefaultCoordinator($writer);

        return $coordinator;
    }

    public function createRunner(
            SimpleTest\Coordinator\CoordinatorInterface $coordinator,
            SimpleTest\Assertion\MakerInterface $assertionMaker,
            \Dhii\Stats\AggregatorInterface $statAggregator)
    {
        $runner = new SimpleTest\Runner\DefaultRunner($coordinator, $assertionMaker, $statAggregator);

        return $runner;
    }

    public function createTester(
            SimpleTest\Coordinator\CoordinatorInterface $coordinator,
            SimpleTest\Runner\RunnerInterface $runner,
            \Dhii\Stats\AggregatorInterface $statAggregator)
    {
        $tester = new SimpleTest\Tester\Tester($coordinator, $runner, $statAggregator);

        return $tester;
    }

    public function createStatAggregator()
    {
        $aggregator = new DefaultAggregator();

        return $aggregator;
    }

    /**
     * @since [*next-version*]
     * @return \Dhii\SimpleTest\Test\MyTestSource
     */
    public function createTestSource()
    {
        return new MyTestSource();
    }

    public function createSuite($tests, $code, SimpleTest\Coordinator\CoordinatorInterface $coordinator)
    {
        $suite = new SimpleTest\Suite\DefaultSuite($code, $coordinator);
        $suite->addTests($tests);

        return $suite;
    }

    public function generateTester(
        $writer = 1,
        SimpleTest\Coordinator\CoordinatorInterface $coordinator = null,
        \Dhii\Stats\AggregatorInterface $statAggregator = null
    ) {
        $verbosity = is_numeric($writer) ? intval($writer) : 1;
        $writer = $writer instanceof SimpleTest\Writer\WriterInterface ? $writer : $this->createWriter($verbosity);
        $coordinator = $coordinator ? $coordinator : $this->createCoordinator($writer);
        $statAggregator = $statAggregator ? $statAggregator : $this->createStatAggregator();
        $assertionMaker = $this->createAssertionMaker();
        $runner = $this->createRunner($coordinator, $assertionMaker, $statAggregator);
        $tester = $this->createTester($coordinator, $runner, $statAggregator);

        return $tester;
    }

    /**
     * Demonstrates how a callback iterator can be used.
     *
     * @since [*next-version*]
     */
    public function demoCallbackIterator()
    {
        $testSource = $this->createTestSource();

        $it = new SimpleTest\Collection\CallbackIterator($testSource, function ($key, $value, &$isContinue) {
            if ($value->getKey() === 'Dhii\SimpleTest\FuncTest\MyTestCaseTest#testSuccess') {
                $isContinue = false;
            }
            return $value->getKey();
        });
        foreach ($it as $_key => $_value) {
            echo "{$_value}\n";
        }
    }

    /**
     * Demonstrates how the tests in suites can be run.
     *
     * @since [*next-version*]
     */
    public function demoTesterRunMultipleSuites()
    {
        $writer = $this->createWriter(2);
        $coordinator = $this->createCoordinator($writer);
        $statAggregator = $this->createStatAggregator();
        $tester = $this->generateTester($writer, $coordinator, $statAggregator);

        /* Demonstrates how tests can be added from any Traversable or array.
         * However, tests cannot be added to a suite from another suite,
         * even though a suite is a Traversable, because a test cannot exist in 2 suites
         * simultaneously.
         */
        $source = $this->createTestSource();
        // Identical ways of adding tests from test source
        $suite1 = $this->createSuite($source, 'suite1', $coordinator);
        $suite2 = $this->createSuite($source->getItems(), 'suite2', $coordinator);

        /**
         * Adds suites to a tester to test all suites in one go.
         */
        $tester->addSuite($suite1);
        $tester->addSuite($suite2);

        $results = $tester->runAll();
        /* @var $results ResultSetInterface */

        /**
         * Iterate over test results and display their suite and key.
         * Demonstrates how to iterate over all results produced by a tester, without regard for suites
         */
        foreach ($results as $_idx => $_result) {
            /* @var $_result Dhii\SimpleTest\Test\ResultInterface */
//            var_dump(sprintf('%1$s -> %2$s', $_result->getSuiteCode(), $_result->getKey()));
        }

        /* @var $results SimpleTest\Collection\SequenceIteratorIteratorInterface */
        /**
         * Iterate over results of each test suite, and display aggregated stats for each suite,
         * then take the first result of each suite and display its suite code.
         * Demonstrates how results of each suite can be accessed separately in tester output.
         */
        foreach ($results->getArrayIterator() as $_idx => $_results) {
            /* @var $_results Dhii\SimpleTest\Test\ResultSetInterface */
            $suiteResults = $_results->getResults();
//            var_dump('Memory', $_results->getMemoryTaken());
//            var_dump('Time', $_results->getTimeTaken());
//            var_dump('Tests', $_results->getTestCount());
//            var_dump('Assertions', $_results->getAssertionCount());
            foreach ($suiteResults as $_result) {
//                var_dump($_result->getSuiteCode());

                break;
            }

            /* @var $suiteResults SimpleTest\Collection\SearchableCollectionInterface */
            /*
             * Print key of all results, the key of which contains the letter 'u' in the method name.
             * Demonstrates how results can be searched.
             */
            foreach ($_results->search(function($key, ResultInterface $item, &$isContinue) {
                $pieces = explode('#', $item->getKey());
                if (isset($pieces[1]) && stripos($pieces[1], 'u') !== false) {
                    return $item;
                }
            }) as $_key => $_result) {
//                var_dump('Search result key', $_key);
                /* @var $_result ResultInterface */
//                var_dump($_result->getKey());
            }
        }

        /*
         * Display totals for all tests.
         *
         * Demonstrates how the product of Tester::runAll() is still a valid test result.
         */
        /* @var $results ResultSet */
//        var_dump('Total tests', $results->getTestCount());
//        var_dump('Total time', $results->getTimeTaken());
//        var_dump('Total memory', $results->getMemoryTaken());
    }

    /**
     * Demonstrates how a suite can be iterable to access each test in it.
     *
     * @since [*next-version*]
     */
    public function demoSuiteItems()
    {
        $writer = $this->createWriter(1);
        $coordinator = $this->createCoordinator($writer);
        $source = $this->createTestSource();
        $suite = $this->createSuite($source, 'suite1', $coordinator);
        foreach ($suite as $_idx => $_test)
        {
            var_dump($_test);
        }
    }
}

function stdemoBasicDemo($demoNames)
{
    $className = 'Dhii\SimpleTest\Test\BasicTestCase';
    foreach ($demoNames as $_demo) {
        $testCase = new $className();
        $methodName = "demo{$_demo}";
        $testCase->{$methodName}();
    }
}

stdemoBasicDemo(array(
//        'SuiteItems',
//        'CallbackIterator',
        'TesterRunMultipleSuites',
    ));
