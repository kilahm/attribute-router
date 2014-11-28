<?hh // strict

namespace kilahm\AttributeRouter\Test\Fixtures\Ignore;

use kilahm\AttributeRouter\Handler;
use kilahm\AttributeRouter\Test\Fixtures\MockContainer;

final class HandlerB
{
    <<route('/get/b')>>
    public static function getB(MockContainer $c, Vector<string> $matches) : void
    {
        throw new \Exception('get b');
    }
}
