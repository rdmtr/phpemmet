<?php

namespace PHPEmmet\Tree\Transformer;

use PHPEmmet\Tree\Node;

/**
 * Interface NodeTransformerInterface
 */
interface TransformerInterface
{
    /**
     * @param Node             $node
     * @param \DOMElement|null $element
     *
     * @return \DOMElement
     */
    public function transform(Node $node, \DOMElement $element): \DOMElement;
}
