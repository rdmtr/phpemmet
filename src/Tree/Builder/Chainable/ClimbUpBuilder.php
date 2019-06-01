<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\BuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class ClimbUpHydrator
 */
final class ClimbUpBuilder implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, Node $parent): Node
    {
        $levelUpsCount = $elementAbbreviation;

        while ($levelUpsCount--) {
            $parent = $parent->getParentNode();
        }

        return $parent;
    }
}
