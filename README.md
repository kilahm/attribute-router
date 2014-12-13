attribute-router
================

[![Build Status](https://travis-ci.org/kilahm/attribute-router.svg?branch=master)](https://travis-ci.org/kilahm/attribute-router) [![HHVM Status](http://hhvm.h4cc.de/badge/kilahm/attribute-router.svg)](http://hhvm.h4cc.de/package/kilahm/attribute-router)

A router for Hacklang that uses user defined attributes to define routing for single page applications.

Installation
============

Update your `composer.json` file to include the following line in your `required` block.

```
“kilahm/attribute-router”: “dev-master”
```

Use
===

## Define attributes

To associate a method with an HTTP path, you may associate an attribute with the method.

```php
    <<route('get', '/a')>>
    public static function getA(Container $c, Vector<string> $matches) : void
    {
        ...
    }
```

The attribute `route` must have at least one parameter, but may have two.  If two parameters are present,
the first must be an http verb (`get`, `post`, `put`, or `delete`) and the second is a regular expression to test the
path with. If the first parameter is not in the set listed above, then the path will be routed to this method for any verb.

If there is only one parameter present, the singular parameter is treated as the regular expression and the verb defaults to `any`.

Note that the route compiler will surround your pattern with a beginning of string anchor and an end of string anchor.  So if your pattern is `/path/a`, the path `/path/ab` will not be routed to that method.

All routed methods must be public, static, and accept exactly two parameters.  The first is defined by you,
but all routed methods must have the same type signature.  The second is a vector of strings which
is the set of matches returned from `preg_match` on the regular expression you defined in the attribute.  The first parameter is
is expected to be some sort of IOC container to allow the method to begin instantiating services needed.

## Compile

After defining all of the routes you like through attributes, you must run the compile script

```bash
vendor/bin/scanroutes path/to/search other/path/to/search [--exclude /path/to/exclude [--exclude /other/path/to/exclude ...]]
```

You may specify multiple base paths to search and multiple paths to ignore.  All searched paths will be searched recursively.

Call `vendor/bin/scanroutes --help` for a list of all options.

### Routes.php

After the route compile script is run, two files should be in your project directory (or install target directory if you used `--install-to`).
The `AutoRoutes.php` file is a collection of proxy methods for your route handlers.

The `Routes.php` file exists so you may add routes without annotating them with attributes.  Follow the pattern given in the file to add routes by hand.

If `scanroutes` is run again, it will not overwrite the `Routes.php` file.  This means that if you already created such a file before running the compiler, the
router class will likely not work.

## Examples

```php
    <<route('/pattern/(.*)/(.*)')>>
    public static function patternA(Container $c, Vector<string> $matches) : void
    {
        // If the original path was /pattern/foo/bar, then
        // $matches[0] is ‘/pattern/foo/bar’
        // $matches[1] is ‘foo’
        // $matches[2] is ‘bar’
    }
```

The above route will be called for any http verb and the `$matches` vector will be populated with the results from `preg_match` on the pattern.

```php
    <<route(‘delete’, ‘/user’)>>
    public static function deleteUser(Container $c, Vector<string> $matches) : void
    {
        // $matches == Vector{‘/user’}
    }
```
