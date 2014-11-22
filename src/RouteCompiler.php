<?hh // strict

namespace kilahm\AttributeRouter;

use ReflectionClass;
use ReflectionMethod;
use kilahm\Scanner\ClassScanner;

type RouteParts = shape(
    'pattern' => string,
    'class' => string,
    'method' => string,
);

final class RouteCompiler
{
    private Map<string, RouteParts> $getList= Map{};
    private Map<string, RouteParts> $postList = Map{};
    private Map<string, RouteParts> $putList = Map{};
    private Map<string, RouteParts> $deleteList = Map{};
    private Map<string, RouteParts> $anyList = Map{};

    public static function fromCli(Vector<string> $argv) : this
    {
        return new static(ClassScanner::fromCli($argv)->mapClassToFile());
    }

    public function __construct(private Map<string,string> $classMap)
    {
    }

    public function compile(string $outFileName) : void
    {
        // Require the files given
        $this->includeFiles($this->classMap->toVector());

        // Loop through classmap to find routes
        $this->classMap->mapWithKey(($className, $fileName) ==> {
            $this->findRoutes(new ReflectionClass($className));
        });

        file_put_contents($outFileName, $this->makeRouterContent());
    }

    private function includeFiles(Vector<string> $fileNames) : void
    {
        foreach($fileNames as $fileName){
            /* HH_FIXME[1002] */
            require_once($fileName);
        }
    }

    private function findRoutes(ReflectionClass $reflector) : void
    {
        if(! $reflector->isInstantiable() || ! $reflector->isSubclassOf(Handler::class)) {
            return;
        }
        foreach($reflector->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if($method->isStatic()) {
                continue;
            }
            $routeArgs = Vector::fromItems($method->getAttribute('route'));
            if($routeArgs->count() === 0) {
                continue;
            } elseif($routeArgs->count() === 1) {
                $verb = 'any';
                $pattern = sprintf('#^%s$#', $routeArgs[0]);
            } else {
                $verb = strtolower((string)$routeArgs[0]);
                $pattern = sprintf('#^%s$#', $routeArgs[1]);
            }

            $route = shape(
                'pattern' => $pattern,
                'class' => $reflector->getName(),
                'method' => $method->getName(),
            );

            switch($verb) {
            case 'get' :
                $list = $this->getList;
                break;
            case 'post' :
                $list = $this->postList;
                break;
            case 'put' :
                $list = $this->putList;
                break;
            case 'delete' :
                $list = $this->deleteList;
                break;
            default :
                $list = $this->anyList;
            }

            if($list->containsKey($pattern)) {
                throw new RouteCollisionException('Found multiple instances of pattern ' . $pattern);
            }
            $list[$pattern] = $route;
        }
    }

    private function makeRouterContent() : string
    {
        return
            $this->makeRouterHead() .
            $this->makeSection('get', $this->getList) .
            $this->makeSection('post', $this->postList) .
            $this->makeSection('put', $this->putList) .
            $this->makeSection('delete', $this->deleteList) .
            $this->makeSection('any', $this->getList) .
            $this->makeRouterFoot();
    }

    private function makeRouterHead() : string
    {

        return
<<<'PHP'
<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * This file is generated using the scanroutes executable which looks for
 * routing attribute.
 *
 * To assign routes without using the routing attribute, edit
 * Routes.php
 */

class AutoRoutes<Tcontainer>
{
PHP;

    }

    private function makeSection(string $methName, Map<string,RouteParts> $routes) : string
    {
        // Start the section
        $out =
<<<PHP

    public static function $methName() : Vector<Route<Tcontainer>>
    {
        return Vector
        {
PHP;

        // Loop through routes, building the shapes
        foreach($routes as $route) {
            $out .=
<<<PHP
            shape(
                'pattern' => '{$route['pattern']}',
                'factory' => class_meth(\\{$route['class']}, 'factory'),
                'method' => meth_classer({$route['class']}, {$route['method']}),
            ),
PHP;
        }

        // Close the section
        $out .=
<<<PHP
        };
    }

PHP;
        return $out;
    }

    private function makeRouterFoot() : string
    {
        return PHP_EOL . '}';
    }
}
