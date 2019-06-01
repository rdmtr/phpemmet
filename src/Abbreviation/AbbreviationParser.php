<?php

namespace PHPEmmet\Abbreviation;

/**
 * Class AbbreviationParser
 */
final class AbbreviationParser
{
    /**
     * @var string
     */
    private static $char;

    /**
     * @var array
     */
    private $aggregators;

    /**
     * Abbreviation constructor.
     *
     * @param array $aggregators
     */
    public function __construct(array $aggregators)
    {
        $this->aggregators = $aggregators;
    }

    /**
     * Shifts aggregator.
     *
     * @param string $abbreviation
     *
     * @return string
     */
    public function shiftChar(string &$abbreviation): string
    {
        self::$char = $abbreviation[0];
        $abbreviation = substr($abbreviation, 1);

        return self::$char;
    }

    /**
     * @param string $abbreviation
     *
     * @return string
     */
    public function shift(string &$abbreviation): string
    {
        $remainder = strpbrk($abbreviation, implode($this->aggregators));
        if (false === $remainder) {
            $abbreviationNode = $abbreviation;
            $abbreviation = '';

            return $abbreviationNode;
        }

        // next char in abbreviation is aggregator
        if ($remainder === $abbreviation) {
            return $this->countSameAggregators($abbreviation);
        }

        $lastNode = str_replace($remainder, '', $abbreviation);
        $abbreviation = $remainder;

        return $lastNode;
    }

    /**
     * @param string $abbreviation
     *
     * @return int
     */
    private function countSameAggregators(string &$abbreviation): int
    {
        $count = 1;
        while ($abbreviation[0] === self::$char) {
            $count++;
            $abbreviation = substr($abbreviation, 1);
        }

        return $count;
    }
}
