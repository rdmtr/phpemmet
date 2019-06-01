<?php

namespace PHPEmmet\Tests\Tree;

use PHPEmmet\Tree\Node;
use PHPUnit\Framework\TestCase;

/**
 * Class NodeTest
 */
class NodeTest extends TestCase
{
    /**
     * Test append child
     */
    public function testAppendChild()
    {
        $node = new Node('a');
        $node->appendChild(new Node('b'));
        $node->appendChild(new Node('c'));

        $this->assertCount(2, $node->getChildren());
        $this->assertEquals('b', $node->getChildren()[0]->getName());
        $this->assertEquals('c', $node->getChildren()[1]->getName());
    }

    /**
     * Test append
     */
    public function testAppend()
    {
        $node = new Node('a');
        $node->appendChild($b = new Node('b'));
        $node->appendChild($c = new Node('c'));
        $node->appendChild($b = new Node('d'));

        $this->assertCount(3, $node->getChildren());

        $c->append(new Node('afterC'));

        $this->assertCount(4, $node->getChildren());
        $this->assertEquals('afterC', $node->getChildren()[2]->getName());
        $this->assertEquals('d', $node->getChildren()[3]->getName());
    }
}
