<?php
namespace wtsd\common;

/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.3
*/
class AppKernel
{
    private static $runtime = 'web'; // ['web', 'cli']
    private static $environment = null;

    public function __construct()
    {

        self::getEnvironment();

        date_default_timezone_set(\wtsd\common\Register::get('config', 'timezone'));
    }

    public static function setEnvironment($env)
    {
        self::$environment = $env;
    }

    public static function getEnvironment()
    {
        if (null === self::$environment) {
            $hostname = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : \wtsd\common\Register::get('config', 'default_environment');
            self::setEnvironment(\wtsd\common\Register::get('config', 'environments')[$hostname]);
        }
        return self::$environment;
    }

    public static function setRuntime($runt)
    {
        self::$runtime = $runt;
    }

    public static function getRuntime()
    {
        return self::$runtime;
    }

    public function runWeb()
    {
        try {
            $router = new \wtsd\common\Router();
            $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
            $arr = $router->execute($request->getPathInfo());
            
            if ($arr !== null) {
                $ctlName = $arr[0];
                if (!class_exists($ctlName)) {
                    throw new \Exception('Controller "'.$ctlName.'" not found!');
                }
                $controller = new $ctlName(self::$environment);
                if (count($arr) > 1) {
                    $controller->dispatch($arr[1], $arr[2]);
                } else {
                    $controller->dispatch();
                }
            } else {
                throw new \Exception('Controller "'.$ctlName.'" not found!');
            }
        } catch (\Exception $e) {
            $arr = $router->execute('notfound');
            $controller = new $arr[0]();
            if (count($arr) > 1) {
                $controller->dispatch($arr[1], $arr[2]);
            } else {
                $controller->dispatch();
            }
        }

    }

    public function runConsole($argv)
    {

        self::setRuntime('cli');
        if (count($argv) > 1) {
            $script = '\\'.str_replace('_', '\\', $argv[1]);
            echo "Launching {$script}… ";

            try {
                if (!class_exists($script)) {
                    throw new \Exception(sprintf('Class "%s" does not exist.', $script));
                }
                $service = new $script($argv);
                echo ' done!'.PHP_EOL;
                if (!$service instanceof \wtsd\cli\Script) {
                    throw new \Exception('Class "' . $script . '" should be interface of "Script"');
                }

                $service->run();

            } catch (\Exception $e) {
                echo 'failed!'.PHP_EOL;
                echo $e->getMessage();
                echo PHP_EOL;
            }
        } else {
            /*
            
            // @todo: Check scheduler if it is time to run some script and run it.
            $tasks = Scheduler::getActualTasks();
            if (count($tasks) > 0) {
                // Run script and form an output
            } else {
                echo 'Specify the script name: ' . PHP_EOL . '$ php console.php ScriptName [arg1 [arg2 [...]]]' . PHP_EOL;
            }
            */
        }
    }

}