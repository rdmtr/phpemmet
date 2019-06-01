<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\ChainableBuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class SiblingBuilder.
 */
final class SiblingBuilder implements ChainableBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, ?Node $parent): Node
    {
        $node = new Node($elementAbbreviation);
        $parent->getParentNode()->appendChild($node);

        return $node;
    }
}
