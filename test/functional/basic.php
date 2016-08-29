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
            __DIR__.'/MyTestCaseTest.php',
            __DIR__.'/More/MyTestCase1Test.php',
            __DIR__.'/More/EvenMore/MyTestCase2Test.php',
        )); // A set of specific test files


        return $locator->locate();
    }
}

// Demonstrates how everything is de-coupled, and uses DI
$writer = new SimpleTest\Writer\DefaultWriter();
$coordinator = new SimpleTest\Coordinator\DefaultCoordinator($writer);
$tester = new SimpleTest\Tester\DefaultTester($coordinator);
$assertionMaker = new SimpleTest\Assertion\DefaultMaker();
$runner = new SimpleTest\Runner\DefaultRunner($coordinator, $assertionMaker);
$suite = new SimpleTest\Suite\DefaultSuite('default', $runner, $coordinator);

/* Demonstrates how tests can be added from any Traversable or array.
 * However, tests cannot be added to a suite from another suite,
 * even though a suite is a Traversable, because a test cannot exist in 2 suites
 * simultaneously.
 */
$testSource = new MyTestSource();
// The below statements are equivalend
//$suite->addTests($testSource);
//$suite->addTests($testSource->getItems());
$suite->addTests($testSource->getItems());

// Demonstrates how a suite can be iterable to access each test in it.
//foreach ($suite as $_idx => $_test)
//{
//    var_dump($_idx, $_test);
//}

// Demonstrates how the writer will only write messages up until a certain level.
//$writer->setLevel(2);

// Demonstrates how the tests in suites can be run
$tester->addSuite($suite);
$tester->runAll();
