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
class Signup extends Controller
{

    public function run()
    {
        if (Request::getPost('email')) {
            $this->template = 'signup.tpl';
            die('Signing up');
        }
        return [];
    }

}
