<?php
namespace wtsd\common;

use wtsd\common;
use wtsd\common\Template;
use wtsd\common\Register;
use wtsd\misc\Feedback;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Controller
{
    /**
     * Class name for the subclass presenting the controller
     *
     * @var string
     */
    protected $ctl = '';

    protected $config = [];

    protected $onlyAuthorized = false;
    protected $onlyAdmin = false;
    
    protected $code = 200;
    protected $format = 'html';
    protected $template = 'index.tpl';
    protected $templateDir = 'resources/views/default';
    protected $redirectUrl = '/';
    protected $defaultMethod = 'showError';

    /**
     * Permission information for certain access levels
     *
     * @var integer
     */
    protected $_permissions = 0;

    protected $configKey = 'public';

    protected $_urlOffset = 0;
    protected $environment;

    public function __construct($environment = 'DEV')
    {
        $this->environment = $environment;

        $this->request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        $this->config = Register::get('config');
        $this->user = \wtsd\common\Factory::create('User');

    }

    protected function setTemplateDir($dir)
    {
        $this->templateDir = $dir;
    }

    /**
     * Renders the page according to purposes. Can be redeclared if necessary.
     */
    public function renderView($contentsArray = null)
    {

        $contentsArray['labels'] = Register::get('lang');
        $contentsArray['environment'] = $this->environment;

        $additional = $this->getAdditionalContents();

        if (is_array($additional)) {
            $contentsArray = array_merge($contentsArray, $additional);
        }

        $this->setTemplateDir($this->config['template']);
        return $this->render($contentsArray);
    }

    protected function getAdditionalContents()
    {
        $contentsArray = [];

        /* @todo: Additional features need refactoring as well as routing */
        $isMarket = Register::get('config', 'market');

        // Market:
        if ($isMarket) {
            if (isset($_COOKIE['order-data'])) {
                $contentsArray['cookie_values'] = (array) json_decode($_COOKIE['order-data']);
            } else {
                $contentsArray['cookie_values'] = [];
            }
            if ($this->config['is_cart']) {
                $cart = new \wtsd\market\Cart();
                $cart->load();
                $contentsArray['cartCount'] = $cart->getCartCount();
                $contentsArray['cartSum'] = $cart->sum();
            }
            // Content:
            if ($this->config['category-menu']) {
                $pcategory = new \wtsd\market\PCategory();
                $contentsArray['pcategories'] = $pcategory->getPCats(0);
            }
        }

        if ($this->config['is_feedback']) {
            $contentsArray['token'] = Feedback::generateToken();
        }

        if ($this->config['auto_generate_menu']) {
            $contentsArray['menuitems'] = \wtsd\content\Category::getMenu();
        }

        if ($this->config['with_slider']) {
            $slider = new \wtsd\misc\Slider();
            $contentsArray['slider'] = $slider->getByUri($_SERVER['REQUEST_URI']);
        }

        return $contentsArray;
    }

    public function render($arr = '')
    {

        header('Content-type: text/html; charset=utf-8');
        setlocale(LC_ALL, $this->config['locale'], $this->config['locale-2'], $this->config['locale-3']);
        setlocale(LC_TIME, "ru_RU.utf8"); 

        $view = new Template($this->templateDir);
        
        if (is_array($arr)) {
            if (!isset($arr['config'])) {
                $arr['config'] = $this->config;
            }
            $view->assignAll($arr);
        }
        
        if ($this->user->isAuthorized()) {
            $view->assign('isAdminAuthorized', true);
        } else {
            $view->assign('isAdminAuthorized', false);
        }

        try {
            $view->display($this->template);
        } catch (\Exception $e) {
            $this->showError($e->getMessage());
        }

    }

    protected function requestStatus($code)
    {
        $status = array(  
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            301 => 'Redirect',
            302 => 'Redirect',
            303 => 'Redirect',
            304 => 'Not modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code]) ? $status[$code] : $status['500'];
    }

    /**
     * Controlls the workflow of the controller: checks the permissions.
     */
    public function dispatch($action = 'run', $arr = null)
    {
        try {

            if (!Register::get('config', 'no_session')) {
                session_start();
            }

            if (Register::get('config', 'no_www') 
                && ($_SERVER["SERVER_NAME"] == 'www.' . Register::get('config', 'base_url'))) {
                $this->code = 301;
                $this->redirectUrl = sprintf('http://%s%s', Register::get('config', 'base_url'), $_SERVER['REQUEST_URI']);
            }


            if (Register::get('config', 'only_https')) {
                if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "") {
                    $this->code = 301;
                    $this->redirectUrl = sprintf('https://%s%s', Register::get('config', 'base_url'), $_SERVER['REQUEST_URI']);
                }
            }

            if ($this->checkPermissions()) {
                if ($arr !== null) {
                    $output = call_user_func_array(array(&$this, $action), array_values($arr));
                } else {
                    $output = $this->$action();
                }
            } else {
                $output = $this->publicRun();
            }

            header("HTTP/1.1 " . $this->code . " " . $this->requestStatus($this->code), true, $this->code);
            if ($this->code == 200) {
                if ($this->format == 'json') {
                    header('Content-Type: application/json');
                    echo json_encode($output, JSON_NUMERIC_CHECK);
                } elseif ($this->format == 'xml') {
                    header('Content-Type: application/rss+xml; charset=utf-8');
                    echo $this->renderView($output);
                } elseif ($this->format == 'txt') {
                    header('Content-type: text/plain');
                    if (isset($this->attachmentName)) {
                        header('Content-Disposition: attachment; filename="'.$this->attachmentName.'"');
                    }
                    echo $this->renderView($output);
                } else {
                    echo $this->renderView($output);
                }
            } elseif ($this->code == 302 || $this->code == 303) {
                header('Location: ' . $this->redirectUrl, true, $this->code);
            } elseif ($this->code == 404 && $this->format != 'json') {
                header('HTTP/1.0 404 Not Found', true, 404);
                $this->template = '404.tpl';
                echo $this->renderView($output);
            } elseif ($this->code = 409) {
                header('Content-Type: application/json');
                echo json_encode($output);
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }

    }

    /**
     * Checks the permission for the controller and must be implemented in subclasses.
     */
    protected function checkPermissions()
    {
        $user = \wtsd\common\Factory::create('User');
        if ($this->onlyAuthorized || $this->onlyAdmin) {
            if ($user->isAuthorized()) {
                if (!$this->onlyAdmin) {
                    return true;
                }
                return $user->isAdmin();
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Action for unauthorized access. Could also be implemented in subclasses.
     */
    protected function publicRun()
    {
        throw new \Exception('Access denied!');
    }

    /**
     * Must be implemented in subclasses. That is where magic happens.
     */
    public function run()
    {
        //throw new \Exception("The 'run' method should be implemented by child");
    }

    public function showError($msg, $code = 404)
    {
        $config = Register::get('config');

        $this->code = $code;
        if ($code == 404) {
            //header('HTTP/1.0 404 Not Found', true, $code);
            $this->template = '404.tpl';
        }

        $additional = array(
            'type' => 'common',
            'config' => $config,
            'error' => $msg,
            'url' => $this->request->getPathInfo(),//Request::parseUrl(),
            );
        return $additional;
        //$this->renderView($additional);
    }
}