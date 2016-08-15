<?php

namespace Dhii\SimpleTest\Test;

interface TestInterface
{
    const FAILURE = 'failed';
    const ERROR = 'errored';
    const SUCCESS = 'success';
    
    public function getCaseName();
    public function getMethodName();
    public function getStatus();
    public function setStatus($status);
    public function getKey();
    public function getStatusCodes();
    public function setMessage($message);
    public function getMessage();
    public function isSuccessful();
    public function setAssertionCount($assertionCount);
    public function getAssertionCount();
}
