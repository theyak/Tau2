Tau library, version 2
======================

This is a very minimal start to PHP 7 conversion of my standard library that 
I use with my own PHP projects. I've basically been using this to learn
about all the PHP tooling that has come around since the original package
was created. It's not really useful for anything. Some of my goals were to learn:

* Installing editorconfig support in vim
* Using and creating tests for phpunit
* Using PHP CodeSniffer, PHPMD, PHP-CS-Fixer, and Phan to check code. None were perfect.
* Using PHP-CS-Fixer to learn how bad my code is
* Creating a composer package and putting it on [Packagist](https://packagist.org)
* PHP 7 language constructs

For a more complete, and PHP 5 compatible version, please use [Tau](https://github.com/theyak/Tau). 
I wouldn't really suggest using this any more. It was written circa 2010 with no particular
coding style or methodologies in mind. It's not actively maintained or updated, and
therefore I recommend something like the [Nette Framework](https://nette.org/), which is really
more a set of utility routines than a framework.

Most of the functions in the original library are now better used by more modern libraries.
Here are some examples:
* TauHttp can be replaced with [Requests](https://github.com/rmccue/Requests)
* TauDb can be replaced with [dibiphp](https://github.com/dg/dibi) or [Nette Database](https://doc.nette.org/en/2.4/database)
* TauCache can be replaced with [Stash](http://www.stashphp.com/) or [Nette Caching](https://doc.nette.org/en/2.4/caching)
* Tau::dump can be replaced with [VarDumper](https://symfony.com/doc/current/components/var_dumper.html),
 [Kint](https://kint-php.github.io/kint/), or [Tracy](https://tracy.nette.org/)

Installation
------------

### Install with Composer
Currently Tau2 only supports installation via [Composer](https://github.com/composer/composer).

```sh
composer require theyak/tau2:dev-master
```
or

    {
        "require": {
            "theyak/tau2": "dev-master"
        }
    }

In your PHP script, use the standard autoloader.

```php
require "vendor/autoload.php";
```

