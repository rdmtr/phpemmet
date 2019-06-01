<?php

namespace PHPEmmet\Abbreviation;

/**
 * Class GroupsParser
 */
final class GroupsParser
{
    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var string|null
     */
    private $abbreviation;

    /**
     * @var array
     */
    private $groups = [];

    /**
     * @var int
     */
    private $counter;

    /**
     * @param string $abbreviation
     *
     * @return $this
     */
    public function abbreviation(string &$abbreviation): self
    {
        $this->initialized = true;
        $this->abbreviation = &$abbreviation;
        $this->counter = 0;
        $this->groups = [];

        return $this;
    }

    /**
     * @return array
     */
    public function parseGroups(): array
    {
        if (!$this->initialized) {
            throw new \LogicException('Please call "abbreviation" method before parsing.');
        }

        if (empty($matches = $this->matches())) {
            return [];
        }

        list($groupContent, $groupContentPos) = end($matches);
        $this->addGroup($groupContentPos, $groupContent);

        if (!empty($this->matches())) {
            $this->parseGroups();
        }

        $this->initialized = false;

        return array_reverse($this->groups);
    }

    /**
     * @param int    $groupContentPos
     * @param string $groupContent
     */
    private function addGroup(int $groupContentPos, string $groupContent): void
    {
        $groupName = 'group_'.$this->counter;
        $remainder = substr($this->abbreviation, $groupContentPos + 1 + strlen($groupContent));
        $this->abbreviation = substr($this->abbreviation, 0, $groupContentPos - 1).$groupName.$remainder;
        $this->groups[$groupName] = $groupContent;

        $this->counter++;
    }

    /**
     * @return array
     */
    private function matches(): array
    {
        preg_match('/^.*\((.*?)\).*$/', $this->abbreviation, $matches, PREG_OFFSET_CAPTURE);

        return $matches;
    }
}
