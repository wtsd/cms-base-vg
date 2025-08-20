<?php
namespace wtsd\content\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\content\Gallery as cGallery;
use wtsd\content\Image;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Gallery extends AdminController
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
        $obj = new cGallery();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }

        $q = Request::getGet('q');
        $contentsArray = $obj->lst($page, $q);
        
        $this->template = 'content/lst-gallery.tpl';
        return $contentsArray;
    }
    
    public function form($id = 0)
    {
        $obj = new cGallery();

        $id = Request::parseUrl(3);
        
        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['c_type'] = 'gallery';
        $contentsArray['id'] = $id;

        $contentsArray['galleries'] = $obj->getGalleryHierarchy(0);
        $contentsArray['photos'] = Image::getPhotosByGallery($id);

        $this->template = 'content/frm-gallery.tpl';
        return $contentsArray;
    }
        
    public function save()
    {
        $obj = new cGallery();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cGallery();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
}