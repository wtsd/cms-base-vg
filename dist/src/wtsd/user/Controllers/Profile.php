<?php
namespace wtsd\user\Controllers;

use wtsd\common;
use wtsd\common\AdminController;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Profile extends AdminController
{
    protected $template = 'user/profile.tpl';
    
    public function run()
    {
        $user = \wtsd\common\Factory::create('User');
        $contentsArray = array(
            'user' => $user,
        );
        
        return $contentsArray;
    }

    public function save()
    {
        $passwd = \wtsd\common\Request::getPost('passwd');
        $name = \wtsd\common\Request::getPost('name');
        $email = \wtsd\common\Request::getPost('email');
        $tel = \wtsd\common\Request::getPost('tel');
        $f_name = \wtsd\common\Request::getPost('f_name');

        $user = \wtsd\common\Factory::create('User');
        $user->profileUpdate($email, $name, $passwd, $tel);
        $contentsArray = array(
            'user' => $user,
        );
        if ($user->getName() == $name) {
            $contentsArray['status'] = 'saved';
        }

        return $contentsArray;
    }
}