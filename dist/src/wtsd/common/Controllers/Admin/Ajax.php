<?php
namespace wtsd\common\Controllers\Admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Controller;
use wtsd\common\Request;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Ajax extends AdminController
{
    protected $format = 'json';
    protected $method = 'GET';

    public function run()
    {
        $subController = Request::parseUrl(2);
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($subController != '') {
            $ctlName = 'wtsd\common\Controllers\Admin\Ajax\\' . ucfirst($subController);
            $ctl = new $ctlName();
            return $ctl->run();
        } elseif (Request::getPost('model') && Request::getPost('controller')) {
            $args = $_POST;
            $func_name = Request::getPost('controller') . 'Ajax';
            $class_name = 'wtsd\\' . Request::getPost('model');
            $obj = new $class_name();
            $ret = $obj->$func_name($args);
            return $ret;
        }
        exit(0);
    }
    
    protected function publicRun()
    {
        $labels = Register::get('lang');
        $result = array(
            'status' => 'error',
            'msg' => $labels['admin']['need_to_auth'],
            );

        return $result;
    }
}