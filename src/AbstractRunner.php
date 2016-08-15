<?php

namespace Dhii\SimpleTest;

abstract class AbstractRunner extends Test\AbstractSupervisor implements RunnerInterface
{
    protected $writer;
    protected $assertionMaker;
    
    public function __construct(Writer\WriterInterface $writer, Assertion\DefaultMaker $assertionMaker)
    {
        $this->_setWriter($writer);
        $this->_setAssertionMaker($assertionMaker);
    }
    
    protected function _setWriter(Writer\WriterInterface $writer)
    {
        $this->writer = $writer;
        return $this;
    }
    
    public function getWriter() {
        return $this->writer;
    }
    
    protected function _setAssertionMaker(Assertion\MakerInterface $assertionMaker)
    {
        $this->assertionMaker = $assertionMaker;
        return $this;
    }
    
    protected function _getAssertionMaker()
    {
        return $this->assertionMaker;
    }
    
    public function getAssertionStatusCount($status = null)
    {
        return $this->_getAssertionMaker()->getAssertionStatusCount($status);
    }
    
    public function run(Test\TestInterface $test)
    {
        return $this->_run($test);
    }
    
    protected function _run(Test\TestInterface $test)
    {        
        $assertionMaker = $this->_getAssertionMaker();
        $assertionCount = $assertionMaker->getAssertionTotalCount();
        $assertionStatusCount = $assertionMaker->getAssertionStatusCount();
        
        try {
            $className = $test->getCaseName();
            $methodName = $test->getMethodName();
            $case = new $className($this->_getAssertionMaker());
            $this->_beforeTest($test, $case);
            call_user_func_array(array($case, $methodName), array());
        } catch (Assertion\FailedExceptionInterface $exF) {
            return $this->_processTestResult(
                    $test,
                    Test\TestInterface::FAILURE,
                    $exF,
                    $case,
                    $assertionMaker->getAssertionTotalCount() - $assertionCount,
                    $assertionStatusCount,
                    $assertionMaker->getAssertionStatusCount());
            
        } catch (\Exception $exE) {
            return $this->_processTestResult(
                    $test,
                    Test\TestInterface::ERROR,
                    $exE,
                    $case,
                    $assertionMaker->getAssertionTotalCount() - $assertionCount,
                    $assertionStatusCount,
                    $assertionMaker->getAssertionStatusCount());
        }
        
        return $this->_processTestResult(
                $test,
                Test\TestInterface::SUCCESS,
                '',
                $case,
                $assertionMaker->getAssertionTotalCount() - $assertionCount,
                $assertionStatusCount,
                $assertionMaker->getAssertionStatusCount());
    }
    
    protected function _processTestResult(Test\TestInterface $test, $status, $message, CaseInterface $case, $assertionCount, $oldAssertionStatusCount, $newAssertionStatusCount)
    {
        $test->setStatus($status);
        if (!$test->isSuccessful()) {
            $test->setMessage($message);
        }
        
        $test->setAssertionCount($assertionCount);
        $this->_addStatusCount($test->getStatus());
        $this->_updateAssertionStatusCounts($oldAssertionStatusCount, $newAssertionStatusCount);
        
        $this->_afterTest($test, $case);
        
        return $status;
    }
    
    protected function _beforeTest(Test\TestInterface $test, CaseInterface $case)
    {
        ob_start();
        $this->getWriter()->writeH4(sprintf('Running Test %1$s', $test->getKey()), Writer\WriterInterface::LVL_2);
        $case->beforeTest();
    }
    
    protected function _afterTest(Test\TestInterface $test, CaseInterface $case)
    {
        $case->afterTest();
        $writer = $this->getWriter();
        $writer->write($this->_getTestMessageText($test), Writer\WriterInterface::LVL_2);
        $writer->writeH5(sprintf('%2$d / %1$s', $this->getTestStatusMessage($test->getStatus()), $test->getAssertionCount()), Writer\WriterInterface::LVL_2);
        ob_end_flush();
    }
    
    protected function _getTestMessageText(Test\TestInterface $test)
    {
        $message = $test->getMessage();
        if ($message instanceof \Exception) {
            if ($test->isSuccessful()) {
                return '';
            }
            
            if ($test->getStatus() === Test\TestInterface::FAILURE) {
                return sprintf('Test failed. Reason: %1$s' . PHP_EOL, $message->getMessage());
            }
        }
        
        return (string) $message . PHP_EOL;
    }
}
