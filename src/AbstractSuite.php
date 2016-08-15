<?php

namespace Dhii\SimpleTest;

abstract class AbstractSuite extends Test\AbstractSupervisor implements SuiteInterface
{
    protected $cases = array();
    protected $code;
    protected $tester;
    protected $tests;
    protected $assertionMaker;
    protected $runner;
    
    public function __construct($code, TesterInterface $tester, RunnerInterface $runner, Assertion\MakerInterface $assertionMaker)
    {
        $this->_setCode($code)
                ->_setTester($tester)
                ->_setAssertionMaker($assertionMaker)
                ->_setRunner($runner);
    }
    
    protected function _setTester(TesterInterface $tester)
    {
        $this->tester = $tester;
        return $this;
    }
    
    public function getTester()
    {
        return $this->tester;
    }
    
    protected function _setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    
    public function getCode() {
        return $this->code;
    }
    
    /**
     * @return string[]
     */
    public function getAllCases()
    {
        return $this->cases;
    }
    
    public function addCase($case)
    {
        $key = $this->_getCaseKey($case);
        $this->cases[$key] = $case;
        
        return $this;
    }
    
    protected function _scanForTests()
    {
        $tests = array();
        foreach ($this->getAllCases() as $_key => $_case) {
            $caseTests = $this->getCaseTests($_case);
            $tests = array_merge($tests, $caseTests);
        }
        
        return $tests;
    }
    
    public function getTests()
    {
        if (is_null($this->tests)) {
            $this->tests = $this->_scanForTests();
            $this->_orderTests($this->tests);
        }
        
        return $this->tests;
    }
    
    /**
     * @param Test\TestInterface[] $tests
     * @return \Dhii\SimpleTest\AbstractSuite
     */
    protected function _orderTests(&$tests)
    {
        return $this;
    }
    
    protected function _getCaseKey($case)
    {
        return $case;
    }
    
    protected function _getCaseRunCode($case)
    {
        return sprintf('%1$s::%2$s', $this->getCode(), $this->_getCaseKey($case));
    }
    
    protected function _getTestRunCode($case, $testKey)
    {
        $caseRunCode = $this->_getCaseRunCode($case);
        list(, $testCode) = explode('#', $testKey);
        $runCode = sprintf('%1$s>%2$s', $caseRunCode, $testCode);
        
        return $runCode;
    }
    
    /**
     * @return RunnerInterface
     */
    protected function _getRunner()
    {
        return $this->runner;
    }
    
    protected function _setRunner(RunnerInterface $runner)
    {
        $this->runner = $runner;
        return $this;
    }
    
    /**
     * 
     * @return Assertion\MakerInterface
     */
    protected function _getAssertionMaker()
    {
        return $this->assertionMaker;
    }
    
    protected function _setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;
        return $this;
    }
    
    public function getCaseTests($case)
    {
        return $this->_getClassTestMethods($case);
    }
    
    public function runAll()
    {
        
        foreach ($this->getTests() as $_test)
        {
            $runner = $this->_getRunner();
            $assertionCounts = $runner->getAssertionStatusCount();
            $statusCounts = $runner->getTestStatusCount();
            $this->_beforeRunTest($runner, $_test);
            $runner->run($_test);
            $this->_updateAssertionStatusCounts($assertionCounts, $runner->getAssertionStatusCount());
            $this->_updateStatusCounts($statusCounts, $runner->getTestStatusCount());
            $this->_afterRunTest($runner, $_test);
        }
        
    }
    
    public function getWriter()
    {
        return $this->getTester()->getWriter();
    }
    
    protected function _beforeRunTest(RunnerInterface $runner, Test\TestInterface $test)
    {
        return $this;
    }

    protected function _afterRunTest(RunnerInterface $runner, Test\TestInterface $test)
    {        
        return $this;
    }
    
    protected function _isTestMethodName($methodName)
    {
        $requiredStart = 'test';
        $startsWith = substr($methodName, 0, strlen($requiredStart));
        
        return $startsWith === $requiredStart;
    }
    
    /**
     * @param object|string $object An object, or a class name.
     * @return \ReflectionClass
     */
    protected function _getObjectReflection($object)
    {
        return new \ReflectionClass($object);
    }
    
    /**
     * @param string $className The name of a test case class.
     *  That class must be a descendant of {@see CaseInterface}.
     * @return Test\TestInterface[]
     */
    protected function _getClassTestMethods($className)
    {
        if (!is_a($className, 'Dhii\\SimpleTest\\CaseInterface', true)) {
            throw new \InvalidArgumentException(sprintf('Could not create class tester: Class "%1$s" is not a valid test case class', $className));
        }
        
        $methods = array();
        $className = $this->_getObjectReflection($className);
        foreach ($className->getMethods(\ReflectionMethod::IS_PUBLIC) as $_method) {
            /* @var $_method \ReflectionMethod */
            if (!$this->_isTestMethodName($_method->name)) {
                continue;
            }
            
            $test = $this->_createTest($_method->class, $_method->name, $key);
            $methods[$test->getKey()] = $test;
        }
        
        return $methods;
    }
    
    /**
     * @param string $className
     * @param string $methodName
     * @param string|null $key Default: auto key made of class and method names.
     * @return Test\DefaultTest
     */
    protected function _createTest($className, $methodName, $key = null)
    {
        if (is_null($key)) {
            $key = sprintf('%1$s#%2$s', $className, $methodName);
        }
        
        return new Test\DefaultTest($className, $methodName, $key);
    }
    
    protected function _simpleDebug($object)
    {
        if (is_object($object)) {
            return sprintf('object(%1$s)', get_class($object));
        }
        
        if (is_string($object)) {
            return sprintf('string(%1$s)', strlen($object));
        }
        
        if (is_numeric($object)) {
            return sprintf('number(%1$s)', $object+0);
        }
        
        return gettype($object);
    }
}
