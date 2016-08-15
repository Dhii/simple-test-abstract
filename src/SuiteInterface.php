<?php

namespace Dhii\SimpleTest;

use Dhii\SimpleTest\Test;

interface SuiteInterface extends
    Assertion\AccountableInterface,
    Test\AccountableInterface,
    Writer\WriterAwareInterface
{
    public function getTests();
    public function getAllCases();
    public function addCase($case);
    public function getCode();
    public function runAll();
    public function getTester();
}
