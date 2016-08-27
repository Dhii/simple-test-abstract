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

$writer = new SimpleTest\Writer\DefaultWriter;
//$writer->setLevel(2);
$tester = new SimpleTest\Tester\DefaultTester($writer);
$assertionMaker = new SimpleTest\Assertion\DefaultMaker();
$runner = new SimpleTest\Runner\DefaultRunner($writer, $assertionMaker);

$suite = new SimpleTest\Suite\DefaultSuite('default', $runner);
//$suite->addCaseSet('Dhii\\SimpleTest\\Test\\MyTestCase');
$testClass = 'Dhii\\SimpleTest\\Test\\MyTestCase';
$errorTest = new SimpleTest\Test\DefaultTest($testClass, 'testError', sprintf('%1$s::%2$s', $testClass, 'testError'));
$tests = array(
    new SimpleTest\Test\DefaultTest($testClass, 'testNothing', sprintf('%1$s::%2$s', $testClass, 'testNothing')),
    new SimpleTest\Test\DefaultTest($testClass, 'testFailure', sprintf('%1$s::%2$s', $testClass, 'testFailure')),
    new SimpleTest\Test\DefaultTest($testClass, 'testSuccess', sprintf('%1$s::%2$s', $testClass, 'testSuccess')),
//    $errorTest,
    $errorTest
);
$suite->addTests($tests);
var_dump($errorTest->getSuiteCode());

$tester->addSuite($suite);
$tester->runAll();

//var_dump($suite->getTests());
