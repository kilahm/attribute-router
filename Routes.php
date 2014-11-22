<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * If you would like routes without using attributes, put them in this file.
 */

class Routes<Tcontainer>
{
    public static function get() : Vector<Route<Tcontainer>>
    {
        return AutoRoutes::get()->addAll(Vector
        {
        /*
        shape(
            'pattern' => '/some regex here/',
            'factory' => class_meth('\full\path\to\class', 'factoryMethod'),
            'method' => meth_caller('\full\path\to\class', 'someInstanceMethod'),
        ),
        shape(
            ...
        ),
        ...
        */
        });
    }

    public static function put() : Vector<Route<Tcontainer>>
    {
        return AutoRoutes::put()->addAll(Vector
        {
        });
    }

    public static function delete() : Vector<Route<Tcontainer>>
    {
        return AutoRoutes::delete()->addAll(Vector
        {
        });
    }

    public static function post() : Vector<Route<Tcontainer>>
    {
        return AutoRoutes::post()->addAll(Vector
        {
        });
    }

    public static function any() : Vector<Route<Tcontainer>>
    {
        return AutoRoutes::any()->addAll(Vector
        {
        });
    }
}
