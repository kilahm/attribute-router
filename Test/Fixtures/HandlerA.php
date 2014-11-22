<?hh // strict

namespace kilahm\AttributeRouter\Test\Fixtures;

use kilahm\AttributeRouter\Handler;

final class HandlerA extends Handler<MockContainer>
{
    public static function factory(MockContainer $c) : this
    {
        return new static();
    }

    <<route('get', '/get/a')>>
    public function getA() : void
    {
    }

    <<route('post', '/post/a')>>
    public function postA() : void
    {
    }

    <<route('put', '/put/a')>>
    public function putA() : void
    {
    }

    <<route('delete', '/delete/a')>>
    public function deleteA() : void
    {
    }

    <<route('/any/a')>>
    public function anyA() : void
    {
    }

    <<route('/pattern/(.*)/(.*)')>>
    public function patternA() : void
    {
        throw new \Exception(implode(' -- ', $this->getMatches()));
    }
}
