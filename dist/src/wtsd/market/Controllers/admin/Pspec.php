<?php
namespace wtsd\market\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\market\PSpec as cPSpec;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Pspec extends AdminController
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
        $obj = new cPSpec();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }
        $contentsArray = $obj->lst($page);
        
        $this->template = 'market/lst-pspec.tpl';
        return $contentsArray;
    }

    public function form($id = 0)
    {
        $obj = new cPSpec();
        $pcategory = new \wtsd\market\PCategory();

        $id = Request::parseUrl(3);
        
        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['stypes'] = $obj->getStypes();
        $contentsArray['pcategories'] = $pcategory->getAll();;
        $contentsArray['c_type'] = 'pspec';
        $contentsArray['id'] = $id;

        $this->template = 'market/frm-pspec.tpl';
        return $contentsArray;
    }

}