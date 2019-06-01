<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\BuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class SiblingHydrator.
 */
final class SiblingBuilder implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, Node $parent): Node
    {
        $node = new Node($elementAbbreviation);
        $parent->getParentNode()->appendChild($node);

        return $node;
    }
}
