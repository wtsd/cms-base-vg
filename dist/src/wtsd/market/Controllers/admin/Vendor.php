<?php
namespace wtsd\market\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\market\Vendor as cVendor;
use wtsd\market\PCategory;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Vendor extends AdminController
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
        $obj = new cVendor();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }
        $contentsArray = $obj->lst($page);
        
        $this->template = 'market/lst-vendor.tpl';
        return $contentsArray;
    }
    
    public function form($id = 0)
    {
        $obj = new cVendor();

        $id = Request::parseUrl(3);

        //$contentsArray['photos'] = cVendor::getPhotos($id);

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['c_type'] = 'vendor';
        $contentsArray['id'] = $id;

        $this->template = 'market/frm-vendor.tpl';
        return $contentsArray;
    }
            
    public function save()
    {
        $obj = new cVendor();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cVendor();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
}