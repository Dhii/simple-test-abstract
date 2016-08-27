<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest;
use Dhii\SimpleTest\TestCase;

if (!class_exists('Dhii\\SimpleTest\\ExceptionInterface')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
}

class MyTestCase extends TestCase\AbstractCaseAssertive
{
    public function beforeCase()
    {
//        parent::beforeCase();
//        var_dump('Before Case');
    }

    public function afterCase()
    {
//        parent::beforeCase();
//        var_dump('After Case');
    }

    public function beforeTest()
    {
//        parent::beforeCase();
//        var_dump('Before Test');
    }

    public function afterTest()
    {
//        parent::beforeCase();
//        var_dump('After Test');
    }

    public function testNothing()
    {
//        var_dump('Testing nothing');
    }

    public function testFailure()
    {
//        var_dump('Testing failure');
        $this->assertTrue(false, 'Gotta be right');
    }

    public function testSuccess()
    {
//        var_dump('Testing failure');
        $this->assertFalse(false, 'Gotta be wrong');
    }

    public function testError()
    {
//        var_dump('Testing failure');
        throw new \Exception('Something went wrong');
    }
}

class MyTestSource extends AbstractSource
{
    public function getTests() {
        $testClass = 'Dhii\\SimpleTest\\Test\\MyTestCase';
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
}

// Demonstrates how everything is de-coupled, and uses DI
$writer = new SimpleTest\Writer\DefaultWriter();
$tester = new SimpleTest\Tester\DefaultTester($writer);
$assertionMaker = new SimpleTest\Assertion\DefaultMaker();
$runner = new SimpleTest\Runner\DefaultRunner($writer, $assertionMaker);
$suite = new SimpleTest\Suite\DefaultSuite('default', $runner);

/* Demonstrates how tests can be added from any Traversable or array.
 * However, tests cannot be added to a suite from another suite,
 * even though a suite is a Traversable, because a test cannot exist in 2 suites
 * simultaneously.
 */
$suite->addTests(new MyTestSource());

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
