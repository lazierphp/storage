Lazier: Storage Component
=========================

_Lazier Storage_ provides a simple key-value store. Often you want to bootstrap an application and
use a file-based key-value store. Maybe you want to use to another adapter later.
For this case there are different adapters for this component.

Lazier Storage is inspired by the LocalStorage and SessionStorage known from web browsers.
In addition, the JSON adapter is compatible with the one from the package `webmozart/key-value-store`,
which is no longer compatible with PHP 8.


Installation
------------

Using Composer:

    composer require lazier/storage


Usage
-----

Example:

```php
<?php

use Lazier\Storage\Adapter\JsonFileStorage;
use Lazier\Storage\SimpleStorage;

$store = SimpleStorage::createWith(JsonFileStorage::create(__DIR__ . '/test.json'));
$store->clear();
$store->setItem('foo', 'bar');
$store->getItem('foo'); // returns "bar"
$store->removeItem('foo');
count($store); // 0
```

You can also use adapters directly:

```php
<?php

use Lazier\Storage\Adapter\EnvStorage;

$store = EnvStorage::create();

if ($store->getItem('APP_ENV') === 'dev') {
    echo 'Running in dev environment...' . PHP_EOL;
}
```
