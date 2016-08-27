<?php

namespace Dhii\SimpleTest\Writer;

/**
 * A default writer implementation.
 *
 * @since [*next-version*]
 */
class DefaultWriter extends AbstractWriter
{
    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function write($text, $level = self::LVL_1)
    {
        if ($this->_isShouldWriteLevel($level)) {
            echo $text;
        }

        return $this;
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function writeH5($text, $level = self::LVL_1)
    {
        $this->writeLine(str_pad(' ' . $text, static::LINE_WIDTH, static::DEC_CHAR_3, STR_PAD_LEFT), $level);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function writeH4($text, $level = self::LVL_1)
    {
        $this->writeLine('', $level);
        $this->writeLine($text, $level);
        $this->writeLine(str_pad('', static::LINE_WIDTH, static::DEC_CHAR_1, STR_PAD_RIGHT), $level);
    }

    /**
     * @inheritdoc
     * @since [*next-version*]
     */
    public function writeH2($text, $level = self::LVL_1)
    {
        $this->writeLine('', $level);
        $this->writeLine(str_pad('', static::LINE_WIDTH, static::DEC_CHAR_1, STR_PAD_RIGHT), $level);
        $this->writeLine(str_pad(sprintf('%1$s %2$s', static::DEC_CHAR_2, $text) . ' ', static::LINE_WIDTH-1, ' ', STR_PAD_RIGHT) . ' ', $level);
        $this->writeLine(str_pad('', static::LINE_WIDTH, static::DEC_CHAR_1, STR_PAD_RIGHT), $level);
        $this->writeLine('', $level);
    }
}
