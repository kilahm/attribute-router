<?hh // strict

namespace kilahm\AttributeRouter\Test\Fixtures\Ignore;

use kilahm\AttributeRouter\Handler;
use kilahm\AttributeRouter\Test\Fixtures\MockContainer;

final class HandlerB extends Handler<MockContainer>
{
    public static function factory(MockContainer $c) : this
    {
        return new static();
    }
    <<route('get', '/get/b')>>
    public function getB() : void
    {
    }
}
