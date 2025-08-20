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
class Logout extends AdminController
{
    
    public function run()
    {
        $this->user->logout();
        
        $this->code = 302;
        $this->redirectUrl = '/adm/';

        return [];
    }
}