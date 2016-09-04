<?php

namespace Dhii\SimpleTest\Test\Stub\More;

use Dhii\SimpleTest\TestCase;

/**
 * A dummy test case.
 * 
 * @since [*next-version*]
 */
class MyTestCase1Test extends TestCase\AbstractCaseAssertive
{
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function beforeTest()
    {
//        parent::beforeCase();
//        var_dump('Before Test');
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function afterTest()
    {
//        parent::beforeCase();
//        var_dump('After Test');
    }

    /**
     * Tests what happens if nothing is done during a test, i.e. no asserions made.
     * 
     * @since [*next-version*]
     */
    public function testNothing()
    {
//        var_dump('Testing nothing');
    }

    /**
     * Tests what happens if a test contains failed assertions.
     * 
     * @since [*next-version*]
     */
    public function testFailure()
    {
//        var_dump('Testing failure');
        $this->assertTrue(true, 'Gotta be right 1');
        $this->assertTrue(false, 'Gotta be right 2');
    }

    /**
     * Tests what happens if a test contains only successful assertions.
     * 
     * @since [*next-version*]
     */
    public function testSuccess()
    {
//        var_dump('Testing failure');
        $this->assertFalse(false, 'Gotta be wrong');
    }

    /**
     * Tests what happens if a test triggers an error
     * 
     * @since [*next-version*]
     */
    public function testError()
    {
//        var_dump('Testing failure');
        throw new \Exception('Something went wrong');
    }
}
