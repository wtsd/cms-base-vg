<?php
namespace wtsd\content\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\content\Image as cImage;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Image extends AdminController
{

    public function run()
    {
        $action = Request::parseUrl(2);
        if (in_array($action, array('browse', 'lst', 'listing', ''))) {
            $contentsArray = $this->listing();
        } elseif (in_array($action, array('frm', 'edit', 'form', 'add'))) {
            $contentsArray = $this->form();
        }
        
        return $contentsArray;
    }

    public function listing()
    {
        $obj = new cImage();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }

        $this->template = 'content/lst-image.tpl';

        $contentsArray = $obj->lst($page);
        return $contentsArray;
    }    

    public function form($id = 0)
    {
        $obj = new cImage();
        $gallery = new \wtsd\content\Gallery();

        $id = Request::parseUrl(3);
        
        $this->template = 'content/frm-image.tpl';

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['galleries'] = $gallery->getAll();;
        $contentsArray['c_type'] = 'image';
        $contentsArray['id'] = $id;

        return $contentsArray;
    }
            
    public function save()
    {
        $obj = new cImage();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cImage();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
}