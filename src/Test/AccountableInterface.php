<?php

namespace Dhii\SimpleTest\Test;

interface AccountableInterface
{
    const TEST_SUCCESS = TestInterface::SUCCESS;
    const TEST_ERROR = TestInterface::ERROR;
    const TEST_FAILURE = TestInterface::FAILURE;
    
    public function getTestStatusCount($status = null);
    public function getTotalTestCount();
    public function getTestStatusCodes();
}
