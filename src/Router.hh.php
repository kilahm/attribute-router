<?hh //strict

namespace kilahm\AttributeRouter;

use kilahm\Routes;
use kilahm\HttpVerb;


type Route = shape(
    'pattern' => string,
    'factory' => HandlerFactory,
    'method' => HandlerMethod,
);

type HandlerFactory = (function (...) : Handler);
type HandlerMethod = (function(Handler) : void);

final class Router implements Containable
{
    public static function factory(IOCContainer $c) : this
    {
        return new static($c);
    }

    public function __construct(private IOCContainer $container)
    {
    }

    public function match(string $path, HttpVerb $verb) : bool
    {
        $success = false;
        switch($verb)
        {
        case HttpVerb::Get :
            $success = $this->attempt($path, Routes::get());
            break;
        case HttpVerb::Put :
            $success = $this->attempt($path, Routes::put());
            break;
        case HttpVerb::Post :
            $success = $this->attempt($path, Routes::post());
            break;
        case HttpVerb::Delete :
            $success = $this->attempt($path, Routes::delete());
            break;
        }
        return $success ? true : $this->attempt($path, Routes::any());
    }

    private function attempt(string $path, Vector<Route> $routes) : bool
    {
        $matches = [];
        foreach($routes as $route) {
            if(preg_match($route['pattern'], $path, $matches)) {
                $route['method']($route['factory']($this->container)->setMatches($matches));
                return true;
            }
        }
        return false;
    }

}
