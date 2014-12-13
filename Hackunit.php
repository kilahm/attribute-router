<?hh // partial

$routesFile = __DIR__ . '/Routes.php';
$autoRoutesFile = __DIR__ . '/AutoRoutes.php';

if( ! (is_file($routesFile) && is_file($autoRoutesFile))) {
    echo 'Run bin/scanroutes Test/Fixtures/ before running the tests.';
    exit(1);
}

spl_autoload_register((string $class) ==> {
    require_once dirname(__DIR__) . '/AutoRoutes.php';
    require_once dirname(__DIR__) . '/Routes.php';
});
