<?php
namespace wtsd\common\Controllers\Admin\Ajax;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Template extends AdminController
{   

    public function run()
    {
        $template = Request::parseUrl(3);
        
        $this->template = $template;

        echo $this->render([]);
        die();
        //return [];
    }
}