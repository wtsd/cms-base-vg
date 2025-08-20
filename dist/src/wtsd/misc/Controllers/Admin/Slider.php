<?php
namespace wtsd\misc\Controllers\Admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\misc\Slider as cSlider;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Slider extends AdminController
{   
    public function run()
    {
        $action = Request::parseUrl(2);
        if (in_array($action, array('browse', 'lst', 'listing'))) {
            return $this->listing();
        } elseif (in_array($action, array('frm', 'edit', 'form', 'add'))) {
            return $this->form();
        } else {
            return $this->listing();
        }
    }

    public function listing()
    {
        $obj = new cSlider();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }
        $contentsArray = $obj->lst($page);
        
        $this->template = 'lst-slider.tpl';
        return $contentsArray;
    }    

    public function form()
    {
        $id = Request::parseUrl(3);

        $obj = new cSlider();
        $category = new \wtsd\content\Category();

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['c_type'] = 'slider';
        $contentsArray['id'] = $id;

        $contentsArray['categories'] = $category->getCatsHierarchy();
        $contentsArray['photos'] = $obj->getPhotos($id);

        $contentsArray['sliders'] = $obj->getAll();

        $this->template = 'frm-slider.tpl';
        return $contentsArray;
    }

    public function save()
    {
        $obj = new cSlider();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cSlider();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
}