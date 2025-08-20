<?php
namespace wtsd\market\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\market\PCategory as cPCategory;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Pcategory extends AdminController
{

    public function run()
    {
        $action = Request::parseUrl(2);
        if (in_array($action, array('browse', 'lst', 'listing', ''))) {
            return $this->listing();
        } elseif (in_array($action, array('frm', 'edit', 'form', 'add'))) {
            return $this->form();
        }
    }

    public function listing()
    {
        $obj = new cPCategory();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }
        $contentsArray = $obj->lst($page);
        
        $this->template = 'market/lst-pcategory.tpl';
        //$this->template = 'market/lst-pcategory-angular.tpl';
        return $contentsArray;
    }

    public function form($id = 0)
    {
        $obj = new cPCategory();

        $id = Request::parseUrl(3);
        
        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['pcategories'] = $obj->getAll();;
        $contentsArray['c_type'] = 'pcategory';
        $contentsArray['id'] = $id;

        $this->template = 'market/frm-pcategory.tpl';
        return $contentsArray;
    }

}