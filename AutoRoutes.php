<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * This file is generated using the scanroutes executable which looks for
 * routing attribute.
 *
 * To assign routes without using the routing attribute, edit
 * Routes.php
 */

class AutoRoutes<Tcontainer>
{
    public static function get() : Vector<Route<Tcontainer>>
    {
        return Vector
        {            shape(
                'pattern' => '#^/get/a$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\HandlerA, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\HandlerA, getA),
            ),            shape(
                'pattern' => '#^/get/b$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\Ignore\HandlerB, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\Ignore\HandlerB, getB),
            ),        };
    }

    public static function post() : Vector<Route<Tcontainer>>
    {
        return Vector
        {            shape(
                'pattern' => '#^/post/a$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\HandlerA, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\HandlerA, postA),
            ),        };
    }

    public static function put() : Vector<Route<Tcontainer>>
    {
        return Vector
        {            shape(
                'pattern' => '#^/put/a$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\HandlerA, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\HandlerA, putA),
            ),        };
    }

    public static function delete() : Vector<Route<Tcontainer>>
    {
        return Vector
        {            shape(
                'pattern' => '#^/delete/a$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\HandlerA, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\HandlerA, deleteA),
            ),        };
    }

    public static function any() : Vector<Route<Tcontainer>>
    {
        return Vector
        {            shape(
                'pattern' => '#^/get/a$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\HandlerA, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\HandlerA, getA),
            ),            shape(
                'pattern' => '#^/get/b$#',
                'factory' => class_meth(\kilahm\AttributeRouter\Test\Fixtures\Ignore\HandlerB, 'factory'),
                'method' => meth_classer(kilahm\AttributeRouter\Test\Fixtures\Ignore\HandlerB, getB),
            ),        };
    }

}