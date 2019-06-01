<?php

namespace PHPEmmet\Tree;

/**
 * Class AbbreviationNode
 */
final class Node
{
    /**
     * @var string
     */
    private $abbreviation;

    /**
     * @var Node
     */
    private $parentNode;

    /**
     * Nesting level
     *
     * @var int
     */
    private $level = 0;

    /**
     * @var array|Node[]
     */
    private $children = [];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string|null
     */
    private $content = null;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * AbbreviationNode constructor.
     *
     * @param string $abbreviation
     */
    public function __construct(string $abbreviation)
    {
        $this->abbreviation = $abbreviation;
        $modificators = '(\.|\*|\#|\{|\[|\z)';
        preg_match("/^(.*?)$modificators.*$/", $abbreviation, $nameMatches);
        preg_match('/^.*?\{(.*?)\}.*$/', $abbreviation, $contentMatches);
        $this->name = $nameMatches === [] ? $abbreviation : $nameMatches[1];
        $this->content = $contentMatches === [] ? null : $contentMatches[1];

        preg_match("/^.*?\.(.*?)(\*|\#|\{|\[|\z)/", $abbreviation, $classMatches);
        if ($classMatches) {
            if (false !== strpos($classesDesc = $classMatches[1], '.')) { // multiple classes
                $classes = str_replace('.', ' ', $classesDesc);
                $this->attributes['class'] = $classes;
            } else {
                $this->attributes['class'] = $classMatches[1];
            }
        }

        preg_match("/^.*?\#(.*?)$modificators.*$/", $abbreviation, $idMatches);
        if ($idMatches) {
            $this->attributes['id'] = $idMatches[1];
        }

        preg_match("/^.*?\[(.*?)\].*$/", $abbreviation, $attrsMatches);
        if ($attrsMatches) {
            foreach (explode(' ', $attrsMatches[1]) as $attributeDescription) {
                if (false !== strpos($attributeDescription, '=')) { // attribute has value
                    list($attribute, $value) = explode('=', $attributeDescription);
                    $value = trim($value, '"\'');
                } else {
                    list($attribute, $value) = [$attributeDescription, null];
                }

                $this->attributes[$attribute] = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    /**
     * @return Node
     */
    public function getParentNode(): Node
    {
        return $this->parentNode;
    }

    /**
     * @param Node $parentNode
     */
    public function setParentNode(Node $parentNode): void
    {
        $this->parentNode = $parentNode;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getMaxLevel(): int
    {
        if ($this->hasChildren()) {
            $levels = [];
            foreach ($this->children as $child) {
                $levels[] = $child->getMaxLevel();
            }

            return max($levels);
        }

        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return Node
     */
    public function getTopLevelNode(): Node
    {
        if ($this->level === 1) {
            return $this->parentNode;
        }
        if ($this->level === 0) {
            return $this;
        }

        return $this->parentNode->getTopLevelNode();
    }

    /**
     * @return array|Node[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return $this->children !== [];
    }

    /**
     * @param Node $node
     */
    public function appendChild(Node $node)
    {
        $this->children[] = $node;
        $node->setLevel($this->level + 1);
        $node->setParentNode($this);
    }

    /**
     * @param Node $node
     */
    public function append(Node $node)
    {
        $nodes = &$this->parentNode->children;
        $currentKey = $this->parentNode->getChildKey($this);
        if (count($nodes) > $currentKey) {
            $newKeysNodes = [];
            foreach (array_slice($nodes, $currentKey, null, true) as $oldKey => $child) {
                $newKey = $oldKey + 1;
                $newKeysNodes[$newKey] = $child;
            }
            $nodes = array_replace($nodes, $newKeysNodes);
        }
        $nodes[$currentKey + 1] = $node;
        $node->setParentNode($this->parentNode);
        $node->setLevel($this->level);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $nodeName
     *
     * @return array|Node[]
     */
    public function findByName(string $nodeName): array
    {
        $nodes = [];
        if ($this->name === $nodeName) {
            $nodes[] = $this;
        }

        if (!$this->hasChildren()) {
            return $nodes;
        }

        foreach ($this->children as $child) {
            $childNodes = $child->findByName($nodeName);
            if ([] !== $childNodes) {
                $nodes = array_merge($nodes, $childNodes);
            }
        }

        return $nodes;
    }

    /**
     * @param Node $node
     */
    public function removeNode(Node $node): void
    {
        unset($this->children[$this->getChildKey($node)]);
        $this->children = array_values($this->children);
    }

    /**
     * @return Node
     */
    public static function parent(): self
    {
        return new self('document');
    }

    /**
     * @param Node $childrenOwner
     */
    public function pickUpChildren(Node $childrenOwner): void
    {
        foreach ($childrenOwner->getChildren() as $child) {
            $this->appendChild($child);
        }
    }

    /**
     * @return Node
     */
    public function deepCopy(): Node
    {
        $copy = clone $this;
        $copy->children = [];
        foreach ($this->children as $child) {
            $childCopy = $child->deepCopy();
            $copy->appendChild($childCopy);
        }

        return $copy;
    }

    /**
     * @param Node $node
     *
     * @return int
     */
    private function getChildKey(Node $node): int
    {
        foreach ($this->children as $key => $child) {
            if (spl_object_hash($child) === spl_object_hash($node)) {
                return $key;
            }
        }

        throw new \LogicException("Current node doesn't have passed children.");
    }
}
