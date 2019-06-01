<?php

namespace PHPEmmet\Tree\Builder;

use PHPEmmet\Abbreviation\AbbreviationParser;
use PHPEmmet\Tree\Builder\Chainable\ChildBuilder;
use PHPEmmet\Tree\Builder\Chainable\ClimbUpBuilder;
use PHPEmmet\Tree\Builder\Chainable\MultiplicationBuilder;
use PHPEmmet\Tree\Builder\Chainable\SiblingBuilder;
use PHPEmmet\Tree\Node;

/**
 * Class ChainableBuilder.
 */
final class ChainableBuilder implements ChainableBuilderInterface
{
    /**
     * @var array|ChainableBuilderInterface[]
     */
    private $builders;

    /**
     * @var AbbreviationParser
     */
    private $parser;

    /**
     * @var Node|null
     */
    private $tree;

    /**
     * ChainableBuilder constructor.
     *
     * @param array $builders
     */
    public function __construct(array $builders)
    {
        $this->builders = $builders;
        $this->parser = new AbbreviationParser(array_keys($this->builders));
    }

    /**
     * @return self
     */
    public static function default(): self
    {
        return new ChainableBuilder(
            [
                '>' => new ChildBuilder(),
                '^' => new ClimbUpBuilder(),
                '+' => new SiblingBuilder(),
                '*' => new MultiplicationBuilder(),
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function build(string $abbreviation, ?Node $parent): Node
    {
        $parent = $parent ?? $this->initializeParent($abbreviation);

        $aggregator = $this->parser->shiftChar($abbreviation);
        $elementAbbreviation = $this->parser->shift($abbreviation);

        $node = $this->getBuilder($aggregator)->build($elementAbbreviation, $parent);

        if ($abbreviation) {
            $this->build($abbreviation, $node);
        }

        return $this->tree->deepCopy();
    }

    /**
     * @param string $abbreviation
     *
     * @return Node
     */
    private function initializeParent(string &$abbreviation): Node
    {
        $firstNode = new Node($this->parser->shift($abbreviation));
        $this->tree = Node::parent();
        $this->tree->appendChild($firstNode);

        return $firstNode;
    }

    /**
     * @param string $aggregator
     *
     * @return ChainableBuilderInterface
     */
    private function getBuilder(string $aggregator): ChainableBuilderInterface
    {
        if (!array_key_exists($aggregator, $this->builders)) {
            throw new \LogicException(sprintf('Resolver for aggregator "%s" does not exist.', $aggregator));
        }

        return $this->builders[$aggregator];
    }
}
