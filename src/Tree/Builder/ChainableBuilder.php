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
final class ChainableBuilder implements BuilderInterface
{
    /**
     * @var array|BuilderInterface[]
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
    public function build(string $abbreviation, Node $parent = null): Node
    {
        if (null === $parent) { // todo $parent = null needed only for initialization (разбить на 2 метода)
            $firstNode = new Node($this->parser->shift($abbreviation));
            $this->tree = Node::parent();
            $this->tree->appendChild($firstNode);
            $parent = $firstNode;
        }

        $aggregator = $this->parser->shiftChar($abbreviation);
        $elementAbbreviation = $this->parser->shift($abbreviation);

        $node = $this->getBuilder($aggregator)->build($elementAbbreviation, $parent);

        if ($abbreviation) {
            $this->build($abbreviation, $node);
        }

        return $this->tree->deepCopy();
    }

    /**
     * @param string $aggregator
     *
     * @return BuilderInterface
     */
    private function getBuilder(string $aggregator): BuilderInterface
    {
        if (!array_key_exists($aggregator, $this->builders)) {
            throw new \LogicException(sprintf('Resolver for aggregator "%s" does not exist.', $aggregator));
        }

        return $this->builders[$aggregator];
    }
}
