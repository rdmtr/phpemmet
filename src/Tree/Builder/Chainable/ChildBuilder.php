<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\ChainableBuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class ChildBuilder.
 */
final class ChildBuilder implements ChainableBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, ?Node $parent): Node
    {
        $node = new Node($elementAbbreviation);
        $parent->appendChild($node);

        return $node;
    }
}
