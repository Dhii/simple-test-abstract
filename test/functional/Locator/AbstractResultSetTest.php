<?php

namespace Dhii\SimpleTest\FuncTest\Locator;

/**
 * Tests {@see \Dhii\SimpleTest\Locator\AbstractResultSet}.
 *
 * @since 0.1.1
 */
class AbstractResultSetTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since 0.1.1
     *
     * @return \Dhii\SimpleTest\Locator\AbstractResultSet The new instance of the test subject.
     */
    public function createInstance($items = null)
    {
        $mock = $this->mock('Dhii\\SimpleTest\\Locator\\AbstractResultSet')
                ->_validateItem(function($item) {
                    // Nothing needs to happen to mark as valid
                })
                ->new();

        $reflection = $this->reflect($mock);
        $reflection->_construct();
        if (is_array($items)) {
            $reflection->_addItems($items);
        }

        return $mock;
    }

    /**
     * Creates a new instance of a test.
     *
     * @since 0.1.1
     *
     * @param string $class Name of the class for this test.
     * @param string $method Name of the class's method for this test.
     */
    public function createTest($class, $method)
    {
        $mock = $this->mock('Dhii\\SimpleTest\\Test\\AbstractTest')
                ->new();

        $reflection = $this->reflect($mock);
        $reflection->_setCaseName($class);
        $reflection->_setMethodName($method);
        $reflection->_setKey(sprintf('%1$s#%2$s', $class, $method));

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since 0.1.1
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf('Dhii\\SimpleTest\\Locator\\AbstractResultSet', $subject, 'Subject instance is not valid');
    }

    /**
     * Tests whether key-value relationships are honoured by the set when iterating over it.
     *
     * @since 0.1.1
     */
    public function testHonourKeys()
    {
        $items = array(
            $this->createTest('MyCase', 'testOne'),
            $this->createTest('MyCase', 'testTwo'),
            $this->createTest('MyCaseTwo', 'testOne')
        );
        $subject = $this->createInstance($items);

        foreach ($subject as $_idx => $_item) {
            /* @var $_item \Dhii\SimpleTest\Test\AbstractTest */
            $this->assertSame($_idx, $_item->getKey(), 'Item in loop inconsistend with input data');
        }
    }
}
