<?php

namespace Dhii\SimpleTest;

use Dhii\SimpleTest\Test;
use Dhii\SimpleTest\Writer;

interface TesterInterface extends
    Test\AccountableInterface,
    Writer\WriterAwareInterface
{
    public function addSuite(SuiteInterface $suite);
    public function runAll();
}
