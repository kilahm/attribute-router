<?hh // strict

namespace kilahm\AttributeRouter\Test;

use Exception;
use HackPack\HackUnit\Core\TestCase;
use kilahm\AttributeRouter\HttpVerb;
use kilahm\AttributeRouter\Router;
use kilahm\AttributeRouter\Test\Fixtures\MockContainer;
use Routes;

class RouterTest extends TestCase
{
    private static bool $loaded = false;

    <<__Memoize>>
    public static function getRoutes() : Routes
    {
        $c = new MockContainer();
        return $c->getRoutes();
    }

    public function setUp() : void
    {
        if(! self::$loaded) {
            spl_autoload_register((string $class) ==> {
                /* HH_FIXME[1002] */
                require_once dirname(__DIR__) . '/AutoRoutes.php';
                /* HH_FIXME[1002] */
                require_once dirname(__DIR__) . '/Routes.php';
            });
            self::$loaded = true;
        }
    }

    public function testRouterMatchesGetA() : void
    {
        $router = new Router(self::getRoutes());

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Get);
        })->toThrow(Exception::class, 'get a');
    }

    public function testRouterMatchesPostA() : void
    {
        $router = new Router(self::getRoutes());

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Post);
        })->toThrow(Exception::class, 'post a');
    }

    public function testRouterMatchesPutA() : void
    {
        $router = new Router(self::getRoutes());

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Put);
        })->toThrow(Exception::class, 'put a');
    }

    public function testRouterMatchesDeleteA() : void
    {
        $router = new Router(self::getRoutes());

        $this->expectCallable(() ==> {
            $router->match('/a', HttpVerb::Delete);
        })->toThrow(Exception::class, 'delete a');
    }

    public function testRouterMatchesAnyA() : void
    {
        $router = new Router(self::getRoutes());

        foreach(HttpVerb::getValues() as $verb){
            $this->expectCallable(() ==> {
                $router->match('/a/any', $verb);
            })->toThrow(Exception::class, 'any a');
        }
    }

    public function testRouterPassesPatterns() : void
    {
        $router = new Router(self::getRoutes());

        foreach(HttpVerb::getValues() as $verb){
            $this->expectCallable(() ==> {
                $router->match('/pattern/first/second', $verb);
            })->toThrow(Exception::class, '/pattern/first/second -- first -- second');
        }
    }
}
