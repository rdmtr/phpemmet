<?php

namespace PHPEmmet;

use PHPEmmet\Tree\Builder\BuilderInterface;
use PHPEmmet\Tree\Transformer\TransformerInterface;

/**
 * Class Emmet.
 *
 * @see https://docs.emmet.io/abbreviations/syntax/
 */
final class Emmet
{
    /**
     * @var BuilderInterface
     */
    private $treeBuilder;

    /**
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * Resolver constructor.
     *
     * @param BuilderInterface     $builder
     * @param TransformerInterface $transformer
     */
    public function __construct(BuilderInterface $builder, TransformerInterface $transformer)
    {
        $this->treeBuilder = $builder;
        $this->transformer = $transformer;
    }

    /**
     * @param string           $abbreviation
     * @param \DOMElement|null $parentElement
     *
     * @return \DOMDocument
     */
    public function make(string $abbreviation, \DOMElement $parentElement = null): \DOMDocument
    {
        $rootNode = $this->treeBuilder->build($abbreviation, null);
        $wrappedElement = $this->transformer->transform($rootNode, $parentElement ?? DOM::emptyElement());

        if (null === $parentElement) {
            $document = $wrappedElement->ownerDocument;
            // Wrapped element used only for wrapping not nested first level elements (siblings)
            DOM::cloneChildNodes($wrappedElement, $document);
            $document->removeChild($wrappedElement);
        } else {
            $document = $wrappedElement->ownerDocument;
        }

        return $document;
    }
}
