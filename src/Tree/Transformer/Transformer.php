<?php

namespace PHPEmmet\Tree\Transformer;

use PHPEmmet\DOM;
use PHPEmmet\Tree\Node;

/**
 * Class Transformer
 */
final class Transformer implements TransformerInterface
{
    /**
     * @var TransformerInterface
     */
    private $nodeTransformer;

    /**
     * Transformer constructor.
     *
     * @param TransformerInterface $nodeTransformer
     */
    public function __construct(TransformerInterface $nodeTransformer)
    {
        $this->nodeTransformer = $nodeTransformer;
    }

    /**
     * @param Node        $node
     * @param \DOMElement $parentElement
     *
     * @return \DOMElement
     */
    public function transform(Node $node, \DOMElement $parentElement): \DOMElement
    {
        foreach ($node->getChildren() as $childNode) {
            $element = DOM::emptyElement($parentElement->ownerDocument);
            $parentElement->appendChild($element);

            if ($childNode->hasChildren()) {
                $this->transform($childNode, $element);
            }

            // After node children tree are built, transform Dom element accordingly with
            // Node abbreviation implementation

            $element = $this->nodeTransformer->transform($childNode, $element);
        }

        return $parentElement;
    }
}
