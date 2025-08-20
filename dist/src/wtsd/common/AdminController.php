<?php
namespace wtsd\common;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\AppKernel;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/

class AdminController extends Controller
{

    protected $onlyAuthorized = true;
    protected $onlyAdmin = false;
    protected $defaultUrl = '/adm/';

    //protected $_controllerPrefix = 'wtsd\common\Controllers\Admin\\';
    protected $configKey = 'admin';
    protected $_urlOffset = 1;
    protected $templateDir = 'resources/views/admin';

    /*
    public function run()
    {

        $this->user = \wtsd\common\Factory::create('User');
        $this->user->load();

        $this->contents = [
                'isAuthorized' => $this->user->isAuthorized(),
                'isAdmin' => $this->user->isSuperuser(),
                'username' => $this->user->getName(),
                'uid' => $this->user->getId(),
                'user' => $this->user->load(),
                ];

        $action = Request::parseUrl(1);

        if ($action == '') {
            $this->contents = $this->{$this->defaultMethod}();
            return $this->contents;
        } else {
            $methodName = $action . 'Action';
            if (method_exists($this, $methodName)) {
                return $this->$methodName();
            }
            return $this->{$this->defaultMethod}();
        }

        $this->code = 302;
        $this->redirectUrl = $this->defaultUrl;
        return $this->contents;
    }
    */

    public function renderView($additional = null)
    {
        $user = \wtsd\common\Factory::create('User');
        $contentsArray = array(
            'user' => $user,
            'isAuthorized' => $user->isAuthorized(),
            'prefix' => Register::get('config', 'admin_prefix'),
            'labels' => Register::get('lang', 'admin'),
            'username' => $user->getName(),
            'environment' => AppKernel::getEnvironment(),
        );
        $this->setTemplateDir('resources/views/admin');
        if (is_array($additional)) {
            $contentsArray = array_merge($contentsArray, $additional);
        }

        return $this->render($contentsArray);
    }

    protected function publicRun()
    {
        $this->code = 303;
        $this->redirectUrl = '/' . Register::get('config', 'admin_prefix') . '/auth/';
        return [];
    }

}