<?php
namespace wtsd\common\Controllers\Admin;

use wtsd\common;
use wtsd\common\AdminController;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Settings extends AdminController
{
    
    public function run()
    {
        $contentsArray = [];
        
        $this->template = 'settings.tpl';
        return $contentsArray;
    }

}