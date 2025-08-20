<?php
namespace wtsd\common\Controllers\Admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class NotFound extends AdminController
{

    protected $onlyAuthorized = false;
    public function run()
    {
        $contentsArray = [];
        $contentsArray['url'] = Request::parseUrl();
        $this->code = 404;
        $this->template = '404.tpl';
        return $contentsArray;
    }
}
