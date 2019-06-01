<?php

namespace PHPEmmet\Tests\Abbreviation;

use PHPEmmet\Abbreviation\GroupsParser;
use PHPUnit\Framework\TestCase;

/**
 * Class GroupsParserTest
 */
class GroupsParserTest extends TestCase
{
    /**
     * @var GroupsParser
     */
    private $parser;

    /**
     * @dataProvider parserProvider
     *
     * @param array  $expectedGroups
     * @param string $expectedAbbr
     * @param string $abbreviation
     */
    public function testParseGroups(array $expectedGroups, string $expectedAbbr, string $abbreviation)
    {
        $groups = $this->parser->abbreviation($abbreviation)->parseGroups();

        $this->assertEquals($expectedGroups, $groups);
        $this->assertEquals($expectedAbbr, $abbreviation);
    }

    /**
     * @return \Generator
     */
    public function parserProvider(): \Generator
    {
        yield [
            [
                'group_0' => 'header>div*2>span',
            ],
            'group_0+footer',
            '(header>div*2>span)+footer',
        ];
        yield [
            [
                'group_0' => 'p>b',
            ],
            'div>header>div*2>group_0+em',
            'div>header>div*2>(p>b)+em',
        ];
        yield [
            [
                'group_0' => 'div+a',
                'group_1' => 'header>div*2>span',
            ],
            'group_1+footer>group_0*2',
            '(header>div*2>span)+footer>(div+a)*2',
        ];
        yield [
            [],
            'header>div+footer',
            'header>div+footer',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->parser = new GroupsParser();
    }
}