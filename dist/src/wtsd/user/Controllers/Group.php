<?php
namespace wtsd\user\Controllers;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Group extends AdminController
{

    protected $onlyAuthorized = true;
    protected $defaultUrl = '/adm/group/';
    protected $defaultMethod = 'lst';

    public function lst()
    {
        $this->template = 'group/list.tpl';


        $group = new \wtsd\user\Group();
        $this->contents['empty_object'] = $group->getEmpty();
        
        /*$page = Request::parseUrl(2);
        if (is_numeric($page)) {
            $page = $page == '' ? 1 : intval($page);
        } else {
            $page = 1;
        }
        $portion = 20;*/
        /*$this->contents['preUrl'] = $this->defaultUrl;
        $this->contents['pages'] = ceil($client->count() / $portion);
        $this->contents['curPage'] = $page;*/

        return $this->contents;
    }
    
}
