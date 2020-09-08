Tau library, version 2
======================

[![Build Status](https://travis-ci.com/theyak/tau2.svg?branch=master)](https://travis-ci.com/theyak/tau2)
[![codecov.io](https://codecov.io/github/theyak/tau2/coverage.svg?branch=master)](https://codecov.io/github/theyak/tau2?branch=master)

This is a very minimal start to PHP 7 conversion of my standard library that
I use with my own PHP projects. My goal was not to actually re-write a library
of functions that no one uses, but rather to learn about all the PHP tooling
that has come around since the original package was created. Some of my goals
were to:

* Use a consistent coding standard. Original Tau is all over the place.
* Install editorconfig support in vim on Windows (vim-editorconfig vs editorconfig-vim)
* Create tests for Phpunit and looking at various other test suites
* Use PHP CodeSniffer, PHPMD, PHP-CS-Fixer, Psalm, Phan, etc. to check code
* Create a composer package and putting it on [Packagist](https://packagist.org)
* Set up continuous integration with Travis-CI
* Use new features of PHP 7
* Set up code coverage with Phpunit, xdebug, and codecov.io, and maybe coveralls

For a more complete, and PHP 5 compatible version of this library, please use [Tau](https://github.com/theyak/Tau),
although I wouldn't suggest using this any more. It was written circa 2010 with no particular
coding style or methodologies in mind. It's not actively maintained or updated.
Therefore I recommend something like the [Nette Framework](https://nette.org/), which is really
more a set of utility routines than a framework, or [Opulence](https://github.com/opulencephp/Opulence).

When Tau was originally written, circa 2010-2012, there weren't a whole lot of libraries that
had similar functionality as Tau. Today, most of the functions in the original library are now better used by more modern libraries.
Here are some examples:
* TauHttp can be replaced with [Requests](https://github.com/rmccue/Requests) or [Guzzle](http://guzzlephp.org/)
* TauDb can be replaced with [dibiphp](https://github.com/dg/dibi), [Nette Database](https://doc.nette.org/en/2.4/database), or [pdox](https://github.com/izniburak/pdox).
* TauCache can be replaced with [Stash](http://www.stashphp.com/) or [Nette Caching](https://doc.nette.org/en/2.4/caching)
* TauView can be replaced with [Foil](https://github.com/FoilPHP/Foil)
* Tau::dump can be replaced with [VarDumper](https://symfony.com/doc/current/components/var_dumper.html),
 [Kint](https://kint-php.github.io/kint/), or [Tracy](https://tracy.nette.org/)
* Lots of ORMs can be found at [ORM Benchmarks](https://github.com/c9s/forked-php-orm-benchmark)


Installation
------------

### Install with Composer
[Composer](https://github.com/composer/composer) is the recommended installation method.

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
require_once "vendor/autoload.php";
```

### Install source from GitHub
Tau has no dependencies outside of standard PHP extensions such as mysqlnd, and therefore can be installed directly. To install using the source code, issue the following command:

    $ git clone git://github.com/theyak/tau2.git

And include it in your scripts:

```php
require_once '/path/to/Tau/src/Tau.php';
```

You'll probably also want to register an autoloader so that you don't have to manually include each file:

```php
Theyak\Tau::registerAutoloader();
```

You can optionally enable Tau v1 class names so you don't have to use the namespaced variations.
Do this by submitting `true` as a parameter to `registerAutoLoader()`. This isn't recommended,
but may be useful if using older code that thinks it's using the original version of Tau.

```php
Theyak\Tau::registerAutoloader(true);
```


