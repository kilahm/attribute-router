<?hh // strict

namespace kilahm\AttributeRouter\Test;

use HackPack\HackUnit\Core\TestCase;

class RouterTest extends TestCase
{
    private static bool $loaded = false;

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

}
