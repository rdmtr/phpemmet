<?php

namespace PHPEmmet\Tree\Builder;

use PHPEmmet\Tree\Node;

/**
 * Interface ResolverInterface.
 */
interface BuilderInterface
{
    /**
     * @param string $elementAbbreviation
     * @param Node   $parent
     *
     * @return Node
     */
    public function build(string $elementAbbreviation, Node $parent): Node;
}
