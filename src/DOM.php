<?php

namespace PHPEmmet;

/**
 * Class DOM
 */
final class DOM
{
    /**
     * @param \DOMDocument $document
     * @param string       $nodeName
     * @param array        $attributes
     *
     * @return \DOMElement
     */
    public static function element(\DOMDocument $document, string $nodeName, array $attributes = []): \DOMElement
    {
        $element = $document->createElement($nodeName);
        foreach ($attributes as $name => $value) {
            if (is_array($value)) {
                $value = implode(' ', $value);
            }

            $element->setAttribute($name, $value);
        }

        $document->appendChild($element);

        return $element;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public static function emptyElement(\DOMDocument $document = null): \DOMElement
    {
        return self::element($document ?? self::document(), 'empty');
    }

    /**
     * @param \DOMElement|\DOMDocument $elementFrom
     * @param \DOMElement|\DOMDocument $elementTo
     */
    public static function cloneChildNodes($elementFrom, $elementTo)
    {
        if (!$elementFrom->hasChildNodes()) {
            return;
        }

        $nodeList = $elementFrom->childNodes;
        while ($nodeList->length > 0) {
            $node = $nodeList->item(0);
            if ($node) {
                $elementTo->appendChild($node);
            }
        }
    }

    /**
     * @return \DOMDocument
     */
    public static function document(): \DOMDocument
    {
        return new \DOMDocument();
    }
}
