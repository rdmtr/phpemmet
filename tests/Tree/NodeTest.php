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
        $node->appendChild($d = new Node('d'));

        $this->assertCount(3, $node->getChildren());

        $c->append(new Node('afterC'));

        $this->assertCount(4, $node->getChildren());
        $this->assertEquals('afterC', $node->getChildren()[2]->getName());
        $this->assertEquals('d', $node->getChildren()[3]->getName());
    }

    /**
     * Test get max nesting level
     */
    public function testGetMaxLevel()
    {
        $node = new Node('a');
        $node->appendChild($b = new Node('b'));
        $b->appendChild($c = new Node('c'));
        $c->appendChild($d = new Node('d'));

        $this->assertEquals(3, $node->getMaxLevel());
    }

    /**
     * Test class and other modificators parsing
     */
    public function testModificatorsParsing()
    {
        $node = new Node('div.class1');
        $this->assertEquals('class1', $node->getAttributes()['class']);

        $node = new Node('div#id1');
        $this->assertEquals('id1', $node->getAttributes()['id']);

        $node = new Node('div{content}');
        $this->assertEquals('content', $node->getContent());

        $node = new Node('div[data-test=test2]');
        $this->assertEquals('test2', $node->getAttributes()['data-test']);

        $node = new Node('div.class1.class2#identifier{content}[data-test data-test2=test2]');

        $this->assertEquals('content', $node->getContent());
        $this->assertEquals('class1 class2', $node->getAttributes()['class']);
        $this->assertEquals('identifier', $node->getAttributes()['id']);
        $this->assertEquals(null, $node->getAttributes()['data-test']);
        $this->assertEquals('test2', $node->getAttributes()['data-test2']);

        // single class
        $node = new Node('div.class1#identifier');
        $this->assertEquals('class1', $node->getAttributes()['class']);
    }
}
