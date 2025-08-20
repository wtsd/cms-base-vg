<?php
namespace wtsd\market\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Branch extends AdminController
{

    protected $onlyAuthorized = true;
    protected $onlyAdmin = true;
    protected $defaultUrl = '/adm/branch/';
    protected $defaultMethod = 'lst';

    public function lst()
    {
        $this->template = 'branch/list.tpl';

        $page = Request::parseUrl(2);
        if (is_numeric($page)) {
            $page = $page == '' ? 1 : intval($page);
        } else {
            $page = 1;
        }
        $portion = 20;

        $branch = new \wtsd\market\Branch();
        $this->contents['empty_object'] = $branch->getEmpty();

        $this->contents['branches'] = $branch->getAll();
        $this->contents['statuses'] = $branch->getStatuses();

        $this->contents['cities'] = \wtsd\geo\City::getAllSelect();
        $this->contents['countries'] = \wtsd\geo\Country::getAllSelect();
        /*$group = new \wtsd\user\Group();
        $groups = $group->getAll();
        foreach ($groups as $gr) {
            $this->contents['groups'][$gr['id']] = $gr['name'];
        }*/
        
        $this->contents['preUrl'] = $this->defaultUrl;
        $this->contents['pages'] = ceil($branch->count() / $portion);
        $this->contents['curPage'] = $page;

        return $this->contents;
    }
    
}
