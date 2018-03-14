<?php

namespace anvein\bx_creator\tests\tools;

use PHPUnit_Framework_TestCase;
use anvein\bx_creator\tools\Color;
use Exception;

class ColorTest extends PHPUnit_Framework_TestCase
{
    /**
     * no_doc.
     */
    public function testColorizeExc1()
    {
        try {
            Color::col();
        } catch (Exception $e) {
            return;
        }
        $this->fail('Нет ошибки, когда аргументы не переданы');
    }

    /**
     * @param null $string
     * @param null $color
     * @param $result
     *
     * @dataProvider providerColorize
     */
    public function testColorize($string = null, $color = null, $result)
    {
        $this->assertEquals(
            $result,
            Color::colorize($string, $color)
        );
    }

    /**
     * @param null $string
     * @param null $color
     * @param $result
     * @dataProvider providerColorize
     */
    public function testCol($string = null, $color = null, $result)
    {
        $this->assertEquals(
            $result,
            Color::col($string, $color)
        );
    }

    /**
     * @return array
     */
    public function providerColorize()
    {
        return [
            ['строка', 'r', "\033[31m" . 'строка' . "\033[37m"],
            ['строка', 'red', "\033[31m" . 'строка' . "\033[37m"],
            ['строка', null, "\033[37m" . 'строка' . "\033[37m"],
            ['строка', 'white', "\033[37m" . 'строка' . "\033[37m"],
            ['строка', 'something_color', "\033[37m" . 'строка' . "\033[37m"],
            [null, null, ''],
        ];
    }
}
