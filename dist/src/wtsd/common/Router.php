<?php
namespace wtsd\common;

class Router
{
    private static $routes = [];
    
    public function __construct()
    {

        $routing = Register::get('routing');
        foreach ($routing as $regex => $controller) {
            $this->route($regex, $controller);
        }

    }

    private function __clone()
    {

    }
    
    public function route($pattern, $callback)
    {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '(\/){0,1}$/';
        self::$routes[$pattern] = $callback;
    }
    
    public function execute($uri)
    {
        $url = explode('?', $uri)[0];
        // (\w+)
        foreach (self::$routes as $pattern => $callback) {
            if (@preg_match($pattern, $url, $params)) {
                array_shift($params);
                $arr = explode('::', $callback);
                $arr[] = $params;

                return $arr;
            }
        }
        throw new \Exception('route was not found');
    }
}