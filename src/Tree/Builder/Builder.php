<?php

namespace PHPEmmet\Tree\Builder;

use PHPEmmet\Abbreviation\GroupsParser;
use PHPEmmet\Tree\Node;

/**
 * Class Builder
 */
final class Builder
{
    /**
     * @var GroupsParser
     */
    private $groupsParser;

    /**
     * @var ChainableBuilderInterface
     */
    private $chainableBuilder;

    /**
     * BranchBuilder constructor.
     *
     * @param GroupsParser              $groupsParser
     * @param ChainableBuilderInterface $chainableBuilder
     */
    public function __construct(GroupsParser $groupsParser, ChainableBuilderInterface $chainableBuilder)
    {
        $this->groupsParser = $groupsParser;
        $this->chainableBuilder = $chainableBuilder;
    }

    /**
     * @param string $elementAbbreviation
     *
     * @return Node
     */
    public function build(string $elementAbbreviation): Node
    {
        $groups = $this->groupsParser->abbreviation($elementAbbreviation)->parseGroups();
        $tree = $this->chainableBuilder->build($elementAbbreviation, null);

        // Build chains of groups
        $groupTrees = [];
        foreach ($groups as $alias => $groupAbbreviation) {
            $groupTrees[$alias] = $this->chainableBuilder->build($groupAbbreviation, null);
        }

        // Insert nesting groups
        foreach ($groupTrees as $searchAlias => $groupTree) {
            foreach (array_keys($groups) as $alias) {
                if (false === strpos($alias, $searchAlias) || $searchAlias === $alias) {
                    continue;
                }

                $this->insertGroup($groupTrees[$alias], $searchAlias, $groupTree);
            }
        }

        // Insert groups in tree
        foreach ($groupTrees as $alias => $groupTree) {
            $this->insertGroup($tree, $alias, $groupTree);
        }

        return $tree;
    }

    /**
     * @param Node   $tree
     * @param string $groupAlias
     * @param Node   $groupTree
     *
     * @return Node
     */
    private function insertGroup(Node $tree, string $groupAlias, Node $groupTree): void
    {
        foreach ($tree->findByName($groupAlias) as $groupNode) {
            // copying is necessary to preserving tree in original state
            $this->replaceWithGroup($groupNode, $groupTree->deepCopy());
        }
    }

    /**
     * @param Node $groupNode Node which must be replaced with children of $tree
     * @param Node $tree
     */
    private function replaceWithGroup(Node $groupNode, Node $tree): void
    {
        // there are 2 type of replacing
        // 1) a + b when all children go to each of sibling
        // 2) a > b (a > b > c ^ ) when children are inherited by last of group nodes chain (last parent node)
        if (count($groupNode->getChildren()) === 1) {
            foreach ($tree->getTopLevelNode()->getChildren() as $topLevelNode) {
                $topLevelNode->pickUpChildren($groupNode);
            }
        } else { // children amount > 1
            if ($groupNode->getMaxLevel() === 1) {
                $siblings = true;
            } else {
                foreach ($tree->getTopLevelNode()->getChildren() as $topLevelNode) {
                    $topLevelNode->pickUpChildren($groupNode);
                }
            }
        }

        foreach (array_reverse($tree->getChildren()) as $child) {
            $groupNode->append($child);
            if (isset($siblings)) {
                $child->pickUpChildren($groupNode);
            }
        }

        $groupNode->getParentNode()->removeNode($groupNode);
    }
}
