JSON Pretty
=======

[![Build Status](https://travis-ci.org/camspiers/json-pretty.png?branch=master)](https://travis-ci.org/camspiers/json-pretty)

This project provides pretty printing for json in php 5.3.

Installation
------------

Make sure the following is present in your `composer.json` file:

```json
{
    "require": {
        "camspiers/json-pretty": "0.1.*"
    }
}
```

Then run:

    $ composer update

Usage
-----

```php
$jsonPretty = new Camspiers\JsonPretty\JsonPretty;

echo $jsonPretty->prettify(array('test' => 'test'));
```

Pimple
------

If you also install [Pimple](https://github.com/fabpot/Pimple), you can use JsonPretty
as follows:
```php
<?php

namespace Camspiers\JsonPretty;

class Container extends \Pimple
{
    public function __construct()
    {
        $this['json_pretty.class'] = 'Camspiers\JsonPretty\JsonPretty';
        $this['json_pretty'] = $this->share(function ($c) {
            return new $c['json_pretty.class']();
        });
    }
}
```

Then, doing `$container = new Container`, you can get an instance of
JsonPretty in `$container['json_pretty']`.


Testing
-------

To run the unit tests with phpunit installed globally:

	$ phpunit

To run the unit tests with phpunit installed via `composer install --dev`:

	$ vendor/bin/phpunit

