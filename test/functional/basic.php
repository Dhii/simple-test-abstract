<?php

namespace Dhii\SimpleTest\Test;

use Dhii\SimpleTest;

if (!class_exists('Dhii\\SimpleTest\\ExceptionInterface')) {
    require_once(__DIR__ . '/../../vendor/autoload.php');
}

class MyTestCase extends SimpleTest\AbstractCase
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
$tester = new SimpleTest\Tester($writer);
$assertionMaker = new SimpleTest\Assertion\DefaultMaker();
$runner = new SimpleTest\DefaultRunner($writer, $assertionMaker);

$suite = new SimpleTest\DefaultSuite('default', $tester, $runner, $assertionMaker);
$suite->addCase('Dhii\\SimpleTest\\Test\\MyTestCase');

$tester->addSuite($suite);
$tester->runAll();
