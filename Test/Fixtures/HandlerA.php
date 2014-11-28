<?hh // strict

namespace kilahm\AttributeRouter\Test\Fixtures;

use kilahm\AttributeRouter\Handler;

class HandlerA
{
    <<route('get', '/a')>>
    public static function getA(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception('get a');
    }

    <<route('post', '/a')>>
    public static function postA(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception('post a');
    }

    <<route('put', '/a')>>
    public static function putA(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception('put a');
    }

    <<route('delete', '/a')>>
    public static function deleteA(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception('delete a');
    }

    <<route('/a/any')>>
    public static function anyA(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception('any a');
    }

    <<route('/pattern/(.*)/(.*)')>>
    public static function patternA(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception(implode(' -- ', $matches));
    }

    <<route('/noexception')>>
    public static function noException(MockContainer $c, Vector<string> $matches) : void
    {
    }
}
