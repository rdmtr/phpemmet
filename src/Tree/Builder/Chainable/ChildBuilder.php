<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\BuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class ChildHydrator.
 */
final class ChildBuilder implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, Node $parent): Node
    {
        $node = new Node($elementAbbreviation);
        $parent->appendChild($node);

        return $node;
    }
}
