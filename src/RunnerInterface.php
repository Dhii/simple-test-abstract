<?php

namespace Dhii\SimpleTest;

use Dhii\SimpleTest\Test;

interface RunnerInterface extends Test\AccountableInterface, Assertion\AccountableInterface
{
    public function getCode();
    public function run(Test\TestInterface $test);
}
