<?php

namespace PHPEmmet\Tree\Builder;

use PHPEmmet\Tree\Node;

/**
 * Interface ChainableBuilderInterface.
 */
interface ChainableBuilderInterface
{
    /**
     * @param string    $elementAbbreviation
     * @param Node|null $parent
     *
     * @return Node
     */
    public function build(string $elementAbbreviation, ?Node $parent): Node;
}
