<?php
namespace wtsd\event\controllers\Admin;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\AdminController;
use wtsd\content\Category as cCategory;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Event extends AdminController
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
        $obj = new \wtsd\event\Event();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }
        $contentsArray = $obj->lst($page);
        
        $this->template = 'lst-event.tpl';
        return $contentsArray;
    }    

    public function form($id = 0)
    {
        $obj = new \wtsd\event\Event();

        $id = Request::parseUrl(3);

        $gallery = new \wtsd\content\Gallery();

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['c_type'] = 'event';
        $contentsArray['id'] = $id;

        $contentsArray['photos'] = $obj->getPhotos($id);

        $this->template = 'frm-event.tpl';
        return $contentsArray;
    }
    
    public function save()
    {
        $obj = new cCategory();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cCategory();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
    
}