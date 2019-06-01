<?php

namespace PHPEmmet\Tests\Abbreviation;

use PHPEmmet\Abbreviation\AbbreviationParser;
use PHPUnit\Framework\TestCase;

/**
 * Class AbbreviationParserTest
 */
class AbbreviationParserTest extends TestCase
{
    /**
     * @param array $items
     *
     * @dataProvider parserProvider
     */
    public function testShift(array $items)
    {
        $abbr = 'header>div*2>span^^+footer';
        $parser = new AbbreviationParser(['>', '^', '+', '*']);

        foreach ($items as $item) {
            $this->assertEquals($item[0], $parser->shift($abbr));

            if ($item[1] === null) {
                continue;
            }
            $this->assertEquals($item[1], $parser->shiftChar($abbr));
        }

        $this->assertEquals('', $abbr);
    }

    /**
     * @return array
     */
    public function parserProvider(): array
    {
        return [
            [
                [
                    ['header', '>'],
                    ['div', '*'],
                    ['2', '>'],
                    ['span', '^'],
                    ['2', '+'],
                    ['footer', null],
                ],
            ],
        ];
    }
}