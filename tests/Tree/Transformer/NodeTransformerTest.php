<?php

namespace PHPEmmet\Tree\Transformer;

use PHPEmmet\DOM;
use PHPEmmet\Tree\Node;
use PHPUnit\Framework\TestCase;

/**
 * Class NodeTransformerTest
 */
class NodeTransformerTest extends TestCase
{
    /**
     * Test correct node transforming
     */
    public function testTransform()
    {
        $node = new Node('div.class1#identifier{content}[data-test data-test2=test2]');
        $html = (new NodeTransformer())->transform($node, DOM::emptyElement())->ownerDocument->saveHTML();

        $this->assertEquals(
            '<div class="class1" id="identifier" data-test="" data-test2="test2">content</div>',
            trim($html)
        );
    }
}
