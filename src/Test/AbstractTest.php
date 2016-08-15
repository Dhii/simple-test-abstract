<?php

namespace Dhii\SimpleTest\Test;

abstract class AbstractTest implements TestInterface
{
    protected $caseName;
    protected $methodName;
    protected $status;
    protected $key;
    protected $message;
    protected $assertionCount;
    
    public function getCaseName()
    {
        return $this->caseName;
    }
    
    protected function _setCaseName($name)
    {
        $this->caseName = $name;
        return $this;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }
    
    protected function _setMethodName($name)
    {
        $this->methodName = $name;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
    
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    public function getKey()
    {
        return $this->key;
    }
    
    protected function _setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
    public function getStatusCodes()
    {
        return array(
            self::ERROR,
            self::FAILURE,
            self::SUCCESS
        );
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function isSuccessful()
    {
        return $this->getStatus() === self::SUCCESS;
    }
    
    public function setAssertionCount($assertionCount)
    {
        $this->assertionCount = intval($assertionCount);
        return $this;
    }
    
    public function getAssertionCount() {
        return $this->assertionCount;
    }
}
