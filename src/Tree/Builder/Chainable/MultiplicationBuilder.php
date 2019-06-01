<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\BuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class MultiplicationBuilder
 */
final class MultiplicationBuilder implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, Node $parent): Node
    {
        $multiplicationCount = $elementAbbreviation;

        while (--$multiplicationCount) {
            $parent->append($parent);
        }

        return $parent;
    }
}
