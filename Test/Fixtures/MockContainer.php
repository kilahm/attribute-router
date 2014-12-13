<?hh // strict

namespace kilahm\AttributeRouter\Test\Fixtures;

use AutoRoutes;

class MockContainer
{
    <<__Memoize>>
    public function getRoutes() : AutoRoutes
    {
        return new AutoRoutes($this);
    }
}
