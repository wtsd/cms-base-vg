<?php
namespace wtsd\market\controllers;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\Register;
use wtsd\common\Controller;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Signin extends Controller
{

    public function run()
    {
        $this->template = 'signin.tpl';
        if (Request::getPost('email')) {
            die('Signing in');
        }
        return [];
    }

}
