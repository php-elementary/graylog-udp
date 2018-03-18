<h1>The wrapper to send log-message to a Graylog server using the UDP transport</h1>


[![Latest Stable Version](https://poser.pugx.org/elementary/graylog-udp/v/stable)](https://packagist.org/packages/elementary/graylog-udp)
[![License](https://poser.pugx.org/elementary/graylog-udp/license)](https://packagist.org/packages/elementary/graylog-udp)
[![Build Status](https://travis-ci.org/php-elementary/graylog-udp.svg?branch=master)](https://travis-ci.org/php-elementary/graylog-udp)
[![Coverage Status](https://coveralls.io/repos/github/php-elementary/graylog-udp/badge.svg)](https://coveralls.io/github/php-elementary/graylog-udp)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/) and then run

```
composer require elementary/graylog-udp
```

Usage
-----
```php
use elementary\logger\graylog\udp\GraylogUdp;

$ex = new GraylogUdp('TestFacility', 'TestHost', 12201);
$ex->info('TestMessage', ['clientIp' => '127.0.0.1']);
```

Testing and Code coverage
-------
Unit Tests are located in `tests` directory.
You can run your tests and collect coverage with the following command:
```
vendor/bin/phpunit
```
Result of coverage will be output into the `tests/output` directory.

License
-------
For license information check the [LICENSE](LICENSE)-file.