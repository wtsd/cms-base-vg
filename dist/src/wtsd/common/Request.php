<?php
namespace wtsd\common;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Request
{
    public static function getPost($arg = null)
    {
        if ($arg === null) {
            return $_POST;
        }

        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $post = $request->request->get($arg);
        return $post;
    }
    
    public static function getGet($arg = null)
    {
        if ($arg === null) {
            return $_GET;
        }

        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $get = $request->query->get($arg);

        return $get;
    }
    
    /**
     * For .htaccess (mod_rewrite) parsing.
     */
    public static function parseUrl($index = null)
    {
        $request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        $url = $request->getPathInfo();

        if ($index === null) {
            return $url;
        }
        $urlArray = [];
        $urlArray = explode("/", $url);
        array_shift($urlArray);

        if (count($urlArray) > 0) {
            if ($index !== null && isset($urlArray[$index])) {
                return $urlArray[$index];
            } else {
                return null;
            }
        }
    }

    public static function getUrl()
    {

        $request = symRequest::createFromGlobals();

        $url = $request->getPathInfo();

        /*if ($config['rerouting']) {
            $placeholders = array(
                ':from1' => rtrim($url, '/'),
                ':from2' => rtrim($url, '/') . '/',
                );
            $row = \wtsd\common\Database::selectQuery(self::SQL_REROUTE, $placeholders, true);
            if ($row['to']) {
                return $row['to'];
            }
        }*/
        return $url;
    }

    public static function getLanguage()
    {
        $request = symRequest::createFromGlobals();

        return $request->getLocale();
    }
}
