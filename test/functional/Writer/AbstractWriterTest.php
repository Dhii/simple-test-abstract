<?php

namespace Dhii\SimpleTest\FuncTest\Writer;

/**
 * Tests {@see \Dhii\SimpleTest\Writer\AbstractWriter}.
 *
 * @since [*next-version*]
 */
class AbstractWriterTest extends \Xpmock\TestCase
{
    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     * @return \Dhii\SimpleTest\Writer\AbstractWriter The new writer.
     */
    public function createInstance()
    {
        $mock = $this->mock('Dhii\SimpleTest\Writer\AbstractWriter')
                ->_write(function($text) {
                    echo $text;
                })
                ->_getEol("\n")
                ->new();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf('Dhii\SimpleTest\Writer\WriterInterface', $subject, 'Subject is not a valid writer');
    }

    /**
     * Tests whether the writer will write correct data.
     *
     * @since [*next-version*]
     */
    public function testCanWrite()
    {
        $subject = $this->createInstance();
        $level0 = \Dhii\SimpleTest\Writer\WriterInterface::LVL_0;
        $level1 = \Dhii\SimpleTest\Writer\WriterInterface::LVL_1;
        $level2 = \Dhii\SimpleTest\Writer\WriterInterface::LVL_2;
        $level3 = \Dhii\SimpleTest\Writer\WriterInterface::LVL_3;
        $token1 = '[87dh27dfg329a]';
        $token2 = '[98c29rr9823yr]';
        $token3 = '[qad13ir83yr01]';

        // No output expected
        $subject->setLevel($level0);
        ob_start();
        $subject->write($token1, $level1);
        $subject->write($token2, $level2);
        $subject->write($token3, $level3);
        $output0 = ob_get_clean();
        $this->assertEquals(0, strlen($output0), 'Writer produced output when it should not');

        // Only level 1 output expected
        $subject->setLevel(1);
        ob_start();
        $subject->write($token1, $level1);
        $subject->write($token2, $level2);
        $subject->write($token3, $level3);
        $output1 = ob_get_clean();
        $this->assertEquals($token1, $output1, 'Writer did not write the correct output');

        // Level 1 and 2 output expected
        $subject->setLevel(2);
        ob_start();
        $subject->write($token1, $level1);
        $subject->write($token2, $level2);
        $subject->write($token3, $level3);
        $output2 = ob_get_clean();
        $this->assertEquals($token1.$token2, $output2, 'Writer did not write the correct output');

        // All tokens expected in the output with line breaks
        $subject->setLevel(3);
        ob_start();
        $subject->writeLine($token1, $level1);
        $subject->writeLine($token2, $level2);
        $subject->writeLine($token3, $level3);
        $output3 = ob_get_clean();
        $this->assertEquals(implode("\n", array($token1, $token2, $token3)) . "\n", $output3, 'Writer did not write the correct output');
    }
}
