<?hh // strict

namespace kilahm\AttributeRouter;

use AutoRoutes;

final class Router
{
    public function __construct(private AutoRoutes $routes)
    {
    }

    public function match(string $path, HttpVerb $verb) : bool
    {
        $success = false;
        switch($verb)
        {
        case HttpVerb::Get :
            $success = $this->attempt($path, $this->routes->get());
            break;
        case HttpVerb::Put :
            $success = $this->attempt($path, $this->routes->put());
            break;
        case HttpVerb::Post :
            $success = $this->attempt($path, $this->routes->post());
            break;
        case HttpVerb::Delete :
            $success = $this->attempt($path, $this->routes->delete());
            break;
        }
        return $success ? true : $this->attempt($path, $this->routes->any());
    }

    private function attempt(string $path, Vector<Route> $routes) : bool
    {
        $matches = [];
        foreach($routes as $route) {
            if(preg_match($route['pattern'], $path, $matches)) {
                $route['method']($this->routes->getContainer(), Vector::fromItems($matches));
                return true;
            }
        }
        return false;
    }

}
