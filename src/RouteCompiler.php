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
    private string $containerType = '';

    public static function factory(Set<string> $includes, Set<string> $excludes) : this
    {
        $scanner = new ClassScanner($includes, $excludes);
        return new static($scanner->mapClassToFile());
    }

    public function __construct(private Map<string,string> $classMap)
    {
    }

    public function compile(string $outPath) : void
    {
        // Require the files given
        $this->includeFiles($this->classMap->toVector());

        // Loop through classmap to find routes
        $this->classMap->mapWithKey(($className, $fileName) ==> {
            $this->findRoutes(new ReflectionClass($className));
        });

        // Construct the routes found
        file_put_contents($outPath . '/AutoRoutes.php', $this->makeRouterContent());

        // Construct a skeleton hand router if it doesn't exist
        if( ! is_file($outPath . '/Routes.php')) {
            file_put_contents(
                $outPath . '/Routes.php',
                str_replace('@@@tcontainer@@@', $this->containerType, file_get_contents(__DIR__ . '/Routes.skeleton'))
            );
        }
    }

    private function includeFiles(Vector<string> $fileNames) : void
    {
        foreach($fileNames as $fileName){
            /* HH_FIXME[1002] */
            require_once($fileName);
        }
    }

    private function findRoutes(ReflectionClass $class) : void
    {
        if( ! $class->isInstantiable()) {
            return;
        }

        foreach($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if(! $method->isStatic() || ! $this->requiresCorrectParameters($method)) {
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
                'class' => $class->getName(),
                'method' => $method->getName(),
            );

            switch($verb) {
            case 'get' :
                $this->addToList($pattern, $route, $this->getList);
                break;
            case 'post' :
                $this->addToList($pattern, $route, $this->postList);
                break;
            case 'put' :
                $this->addToList($pattern, $route, $this->putList);
                break;
            case 'delete' :
                $this->addToList($pattern, $route, $this->deleteList);
                break;
            default :
                $this->addToList($pattern, $route, $this->anyList);
            }

        }
    }

    private function addToList(string $pattern, RouteParts $route, Map<string, RouteParts> $list) : void
    {
        if($list->containsKey($pattern)) {
            throw new RouteCollisionException('Found multiple instances of pattern ' . $pattern);
        }
        $list->add(Pair{$pattern, $route});
    }

    private function requiresCorrectParameters(ReflectionMethod $method) : bool
    {
        if($method->getNumberOfParameters() !== 2) {
            return false;
        }

        list($factory, $matches)= $method->getParameters();

        if($this->containerType === '') {
            // First time we found a factory
            $this->containerType = $factory->info['type_hint'];
        }
        return
            $this->containerType === $factory->info['type_hint'] &&
            $matches->info['type_hint'] === 'HH\Vector<HH\string>';
    }

    private function makeRouterContent() : string
    {
        return
            $this->makeRouterHead() .
            $this->makeSection('get', $this->getList) .
            $this->makeSection('post', $this->postList) .
            $this->makeSection('put', $this->putList) .
            $this->makeSection('delete', $this->deleteList) .
            $this->makeSection('any', $this->anyList) .
            $this->makeRouterFoot();
    }

    private function makeRouterHead() : string
    {

        return
<<<PHP
<?hh // strict

use kilahm\AttributeRouter\Route;

/**
 * This file is generated using the scanroutes executable which looks for
 * routing attribute.
 *
 * To assign routes without using the routing attribute, edit
 * Routes.php
 */


type Route = shape(
    'pattern' => string,
    'method' => (function({$this->containerType}, Vector<string>) : void),
);

class AutoRoutes extends Routes
{

PHP;

    }

    private function makeSection(string $methName, Map<string,RouteParts> $routes) : string
    {
        // Start the section
        $out =
<<<PHP

    public function $methName() : Vector<Route>
    {
        return parent::$methName()->addAll(Vector
        {
PHP;

        // Loop through routes, building the shapes
        foreach($routes as $route) {
            $out .=
<<<PHP
            shape(
                'pattern' => '{$route['pattern']}',
                'method' => class_meth(\\{$route['class']}::class, '{$route['method']}'),
            ),
PHP;
        }

        // Close the section
        $out .=
<<<PHP

        });
    }

PHP;
        return $out;
    }

    private function makeRouterFoot() : string
    {
        return PHP_EOL . '}';
    }
}
