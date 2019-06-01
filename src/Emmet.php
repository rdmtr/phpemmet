<?php

namespace PHPEmmet;

use PHPEmmet\Abbreviation\GroupsParser;
use PHPEmmet\Tree\Builder\Builder;
use PHPEmmet\Tree\Builder\ChainableBuilder;
use PHPEmmet\Tree\Builder\ChainableBuilderInterface;
use PHPEmmet\Tree\Transformer\NodeTransformer;
use PHPEmmet\Tree\Transformer\Transformer;
use PHPEmmet\Tree\Transformer\TransformerInterface;

/**
 * Class Emmet.
 *
 * @see https://docs.emmet.io/abbreviations/syntax/
 */
final class Emmet
{
    /**
     * @var ChainableBuilderInterface
     */
    private $treeBuilder;

    /**
     * @var TransformerInterface
     */
    private $transformer;

    /**
     * Resolver constructor.
     *
     * @param Builder              $builder
     * @param TransformerInterface $transformer
     */
    public function __construct(Builder $builder, TransformerInterface $transformer)
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
        $rootNode = $this->treeBuilder->build($abbreviation);
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

    /**
     * @return Emmet
     */
    public static function new(): self
    {
        return new self(
            new Builder(new GroupsParser(), ChainableBuilder::default()),
            new Transformer(new NodeTransformer())
        );
    }
}
