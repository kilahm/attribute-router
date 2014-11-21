<?hh // strict

namespace kilahm\AttributeRouter;

use ReflectionClass;

type RouteAsStrings = shape(
    'pattern' => string,
    'factory' => string,
    'method' => string,
);

class RouteCompiler
{
    private Vector<RouteAsStrings> $postList = Vector{};
    private Vector<RouteAsStrings> $putList = Vector{};
    private Vector<RouteAsStrings> $getList= Vector{};
    private Vector<RouteAsStrings> $deleteList = Vector{};
    private Vector<RouteAsStrings> $anyList = Vector{};

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

    }

    private function makeRouterContent() : string
    {
        return
            $this->makeRouterHead() .
            $this->makeSection('get', $this->getList) .
            $this->makeSection('put', $this->getList) .
            $this->makeSection('post', $this->getList) .
            $this->makeSection('delete', $this->getList) .
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
 * Routes.hh.php
 */

class AutoRoutes<Tcontainer>
{
PHP;

    }

    private function makeSection(string $methName, Vector<RouteAsStrings> $routes) : string
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
                'pattern' => {$route['pattern']},
                'factory' => ({$route['factory']}),
                'method' => ({$route['method']}),
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
