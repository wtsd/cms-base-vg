<?php
namespace wtsd\user\Controllers;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\common\User;

/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Auth extends AdminController
{
    protected $onlyAuthorized = false;
    
    public function run()
    {
        if ($this->user->isAuthorized()) {
            $this->code = 302;
            $this->redirectUrl = '/adm/dashboard/';
            return;

        }
        if (Request::getPost('uname')) {
            $login = Request::getPost('uname');
            $pass = Request::getPost('upass');
            $remember_me = Request::getPost('remember_me');

            if ($this->user->authenticate($login, $pass)) {
                $status = 'success';
                $this->code = 302;
                $this->redirectUrl = '/adm/';
                return;
            } else {
                $status = 'error';
            }
        } else {
            $status = 'unauth';

        }
        $contentsArray = ['status' => $status];

        $this->template = 'user/login.tpl';
        return $contentsArray;

    }

}