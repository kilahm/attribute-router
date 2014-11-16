<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * If you would like routes without using attributes, put them in this file.
 */

class Routes
{
    public static function get() : Vector<Route>
    {
        return AutoRoutes::get()->addAll(Vector
        {
        });
    }

    public static function put() : Vector<Route>
    {
        return AutoRoutes::put()->addAll(Vector
        {
        });
    }

    public static function delete() : Vector<Route>
    {
        return AutoRoutes::delete()->addAll(Vector
        {
        });
    }

    public static function post() : Vector<Route>
    {
        return AutoRoutes::post()->addAll(Vector
        {
        });
    }

    public static function any() : Vector<Route>
    {
        return AutoRoutes::any()->addAll(Vector
        {
        });
    }
}
