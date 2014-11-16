<?hh // strict

namespace kilahm\AttributeRouter;

use Routes;

type Route<Tcontainer> = shape(
    'pattern' => string,
    'factory' => (function(Tcontainer) : Handler),
    'method' => (function(Handler) : void),
);

type HandlerFactory = ;
type HandlerMethod = ;

final class Router<Tcontainer>
{
    public function __construct(private Tcontainer $container)
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

    private function attempt(string $path, Vector<Route<Tcontainer>>> $routes) : bool
    {
        $matches = [];
        foreach($routes as $route) {
            if(preg_match($route['pattern'], $path, $matches)) {
                $route['method']($route['factory']($this->arg)->setMatches(Vector::fromItems($matches)));
                return true;
            }
        }
        return false;
    }

}
