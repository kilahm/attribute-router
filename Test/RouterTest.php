<?hh // strict

namespace kilahm\AttributeRouter\Test;

use AutoRoutes;
use Exception;
use HackPack\HackUnit\Core\TestCase;
use kilahm\AttributeRouter\HttpVerb;
use kilahm\AttributeRouter\Router;
use kilahm\AttributeRouter\Test\Fixtures\MockContainer;
use Routes;

class RouterTest extends TestCase
{
    private MockContainer $container;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->container = new MockContainer();
    }

    private function makeRouter() : Router
    {
        return new Router($this->container->getRoutes());
    }

    public function testRouterMatchesGetA() : void
    {
        $router = $this->makeRouter();

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Get);
        })->toThrow(Exception::class, 'get a');
    }

    public function testRouterMatchesPostA() : void
    {
        $router = $this->makeRouter();

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Post);
        })->toThrow(Exception::class, 'post a');
    }

    public function testRouterMatchesPutA() : void
    {
        $router = $this->makeRouter();

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Put);
        })->toThrow(Exception::class, 'put a');
    }

    public function testRouterMatchesDeleteA() : void
    {
        $router = $this->makeRouter();

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Delete);
        })->toThrow(Exception::class, 'delete a');
    }

    public function testRouterMatchesAnyA() : void
    {
        $router = $this->makeRouter();

        foreach(HttpVerb::getValues() as $verb){
            $this->expectCallable(() ==> {
                $router->match('/a/any', $verb);
            })->toThrow(Exception::class, 'any a');
        }
    }

    public function testRouterPassesPatterns() : void
    {
        $router = $this->makeRouter();

        foreach(HttpVerb::getValues() as $verb){
            $this->expectCallable(() ==> {
                $router->match('/pattern/first/second', $verb);
            })->toThrow(Exception::class, '/pattern/first/second -- first -- second');
        }
    }

    public function testIgnoredRoutesAreNotRun() : void
    {
        $router = $this->makeRouter();

        foreach(HttpVerb::getValues() as $verb) {
            $this->expectCallable(() ==> {
                $router->match('/get/b', $verb);
            })->toNotThrow();
        }
    }

    public function testUnregisteredPathsReturnFalse() : void
    {
        $router = $this->makeRouter();

        $this->expect($router->match('/no/matches', HttpVerb::Get))->toEqual(false);
    }

    public function testRegisteredPathReturnTrue() : void
    {
        $router = $this->makeRouter();

        $this->expect($router->match('/noexception', HttpVerb::Get))->toEqual('string');
    }
}
