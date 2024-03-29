PHPEmmet
========

[![codecov.io](https://codecov.io/gh/rdmtr/phpemmet/branch/master/graph/badge.svg)](http://codecov.io/gh/rdmtr/phpemmet/branch/master)
[![Build Status](https://travis-ci.com/rdmtr/phpemmet.png)](https://travis-ci.com/rdmtr/phpemmet)

# Usage

PHPEmmet generate DOM elements using emmet.io [abbreviation syntax](https://docs.emmet.io/abbreviations/syntax/).

For example:
```php
$html = Emmet::new()->make('(header>div*2)+footer>p')->saveHtml();
```
```html
<header>
    <div></div>
    <div></div>
</header>
<footer>
    <p></p>
</footer>
```

Or you can use existing DomElement to add children to it:

```php
$doc = new \DOMDocument();
$elem = $doc->createElement('body');
$doc->appendChild($elem);

$html = $this->emmet->make('(header>div*2)+footer>p', $elem)->saveHTML();
```
```html
<body>
    <header>
        <div></div>
        <div></div>
    </header>
    <footer>
        <p></p>
    </footer>
</body>
```

## Supported Aggregators

```>, ^, +, *```

Used for chainable tree building. Defines child, parent and sibling elements relations.

## Supported Modificators

```.class#id{content}[data-attr data-test=test]```

Defines modification of current element: multiplication and elements descriptions (attributes, content etc.).