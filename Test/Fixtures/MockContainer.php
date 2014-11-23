<?hh // strict

namespace kilahm\AttributeRouter\Test\Fixtures;

use AutoRoutes;
use Routes;

class MockContainer
{
    <<__Memoize>>
    public function getAutoRoutes() : AutoRoutes
    {
        return AutoRoutes::factory($this);
    }

    <<__Memoize>>
    public function getRoutes() : Routes
    {
        return Routes::factory($this);
    }
}
