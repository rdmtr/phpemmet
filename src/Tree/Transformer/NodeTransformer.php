<?php

namespace PHPEmmet\Tree\Transformer;

use PHPEmmet\DOM;
use PHPEmmet\Tree\Node;

/**
 * Class NodeToDomElementTransformer
 */
final class NodeTransformer implements TransformerInterface
{
    /**
     * @param Node        $node
     * @param \DOMElement $element
     *
     * @return \DOMElement
     */
    public function transform(Node $node, \DOMElement $element): \DOMElement
    {
        $transformedElement = $element->ownerDocument->createElement($node->getName());
        DOM::cloneChildNodes($element, $transformedElement);

        $element->parentNode->replaceChild($transformedElement, $element);

        return $transformedElement;
    }
}
