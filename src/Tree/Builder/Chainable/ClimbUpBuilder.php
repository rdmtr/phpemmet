<?php

namespace PHPEmmet\Tree\Builder\Chainable;

use PHPEmmet\Tree\Builder\ChainableBuilderInterface;
use PHPEmmet\Tree\Node;

/**
 * Class ClimbUpBuilder
 */
final class ClimbUpBuilder implements ChainableBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(string $elementAbbreviation, ?Node $parent): Node
    {
        $levelUpsCount = $elementAbbreviation;

        while ($levelUpsCount--) {
            $parent = $parent->getParentNode();
        }

        return $parent;
    }
}
