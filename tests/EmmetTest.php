<?php

namespace PHPEmmet\Tests;

use PHPEmmet\Abbreviation\GroupsParser;
use PHPEmmet\Emmet;
use PHPEmmet\Tree\Builder\Builder;
use PHPEmmet\Tree\Builder\ChainableBuilder;
use PHPEmmet\Tree\Transformer\NodeTransformer;
use PHPEmmet\Tree\Transformer\Transformer;
use PHPUnit\Framework\TestCase;

/**
 * Class EmmetTest
 */
class EmmetTest extends TestCase
{
    /**
     * @var Emmet
     */
    private $emmet;

    /**
     * @dataProvider emmetProvider
     *
     * @param string $expectedHtml
     * @param string $abbreviation
     */
    public function testEmmetWithoutParent(string $expectedHtml, string $abbreviation)
    {
        $html = $this->emmet->make($abbreviation)->saveHTML();

        $this->assertEquals($expectedHtml, trim($html));
    }

    /**
     * Test with parent DOMElement
     */
    public function testEmmetWithParent()
    {
        $doc = new \DOMDocument();
        $elem = $doc->createElement('body');
        $doc->appendChild($elem);

        $html = $this->emmet->make('(header>div*2>span)+footer>(div+a)*2>b', $elem)->saveHTML();

        $this->assertEquals(
            '<body><header><div><span></span></div><div><span></span></div></header><footer><div><b></b></div><a><b></b></a><div><b></b></div><a><b></b></a></footer></body>',
            trim($html)
        );
    }

    /**
     * @return \Generator
     */
    public function emmetProvider(): \Generator
    {
        yield ['<div><header><p></p></header></div>', 'div>header>p'];
        yield ['<div><header></header><footer><div></div></footer></div>', 'div>header+footer>div'];
        yield [
            '<div><header><div><p><span></span></p></div></header><footer></footer></div>',
            'div>header>div>p>span^^^+footer',
        ];
        yield [
            '<div><header><div><p><b></b></p></div><div><p><b></b></p></div></header></div>',
            'div>header>div*2>p{content}>b',
        ];
        yield [
            '<div><p><b></b></p><p><b></b></p></div><div><p><b></b></p><p><b></b></p></div>',
            'div*2>p*2>b',
        ];
        yield [
            '<div><header><div><p><b></b><em></em></p></div><div><p><b></b><em></em></p></div></header></div>',
            'div>header>div*2>p>b+em',
        ];
        yield [
            '<div><header><div><p><b></b></p><em></em></div><div><p><b></b></p><em></em></div></header></div>',
            'div>header>div*2>p>b^+em',
        ];

        // groups

        yield [
            '<div><header><div><p><b></b></p><em></em></div><div><p><b></b></p><em></em></div></header></div>',
            'div>header>div*2>(p>b)+em', // the same as above with grouping
        ];
        yield [
            '<header><div><span></span></div><div><span></span></div></header><footer></footer>',
            '(header>div*2>span)+footer',
        ];

        // siblings without once parent
        yield [
            '<header></header><footer><div><b></b></div><p><b></b></p><div><b></b></div><p><b></b></p></footer>',
            'header+footer>(div+p)*2>b',
        ];

        // with nesting and two parent
        yield [
            '<footer><a><b></b></a><div><p></p><b></b></div><a><b></b></a><div><p></p><b></b></div></footer>',
            'footer>(a+div>p)*2>b',
        ];

        // nested groups
        yield [
            '<div><span><p></p></span><b></b></div><div><span><p></p></span><b></b></div><em></em>',
            '(div*2>(span>p)+b)+em',
        ];

        // group with climb up
        yield [
            '<footer><div><p><m></m></p><a><d></d></a><b></b></div><div><p><m></m></p><a><d></d></a><b></b></div></footer>',
            'footer>(div>p>m^+a>d)*2>b',
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->emmet = Emmet::new();
    }
}
