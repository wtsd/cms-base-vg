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
class User extends AdminController
{

    protected $onlyAuthorized = true;
    protected $onlyAdmin = true;
    protected $defaultUrl = '/adm/user/';
    protected $defaultMethod = 'lst';

    public function lst()
    {

        $this->template = 'user/list.tpl';

        $page = Request::parseUrl(2);
        if (is_numeric($page)) {
            $page = $page == '' ? 1 : intval($page);
        } else {
            $page = 1;
        }
        $portion = 20;

        $user = \wtsd\common\Factory::create('User');
        $this->contents['empty_object'] = $user->getEmpty();

        $this->contents['users'] = $user->getAll();

        $group = new \wtsd\user\Group();
        $groups = $group->getAll();
        foreach ($groups as $gr) {
            $this->contents['groups'][$gr['id']] = $gr['name'];
        }
        
        $this->contents['preUrl'] = $this->defaultUrl;
        $this->contents['pages'] = ceil($user->count() / $portion);
        $this->contents['curPage'] = $page;

        return $this->contents;
    }
    
}
