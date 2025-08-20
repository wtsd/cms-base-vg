<?php
namespace wtsd\market\controllers;

use wtsd\common;
use wtsd\common\Controller;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Cabinet extends Controller
{

    public function run()
    {
        $this->template = 'cabinet.tpl';
        return [];
    }

}
