PHPEmmet
========

[![codecov.io](https://codecov.io/gh/rdmtr/phpemmet/branch/master/graph/badge.svg)](http://codecov.io/gh/rdmtr/phpemmet/branch/master)
[![Build Status](https://travis-ci.com/rdmtr/phpemmet.png)](https://travis-ci.com/rdmtr/phpemmet)

## Algorithm

1) Parsing and building all (with groups) trees
2) Resolving Nodes Tree into DomElements tree with modificators

###Aggregators: ```>, ^, +, *```

Used for chainable tree building. Defines child, parent and sibling elements relations.

###Modificators: ```.class#id{content}```

Defines modification of current element: multiplication and elements descriptions (attributes, content etc.).