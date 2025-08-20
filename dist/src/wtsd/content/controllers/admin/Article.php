<?php
namespace wtsd\content\controllers\admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\content\Article as cArticle;
use wtsd\content\Category;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Article extends AdminController
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
        $obj = new cArticle();

        $page = Request::parseUrl(3);
        if (intval($page) == 0) {
            $page = 1;
        }

        $q = Request::getGet('q');
        $contentsArray = $obj->lst($page, $q);

        $contentsArray['q'] = $q;
        
        $this->template = 'content/lst-article.tpl';
        return $contentsArray;
    }    

    public function form()
    {
        $id = Request::parseUrl(3);

        $obj = new cArticle();
        $category = new \wtsd\content\Category();

        $contentsArray['obj'] = $obj->getById($id);
        $contentsArray['c_type'] = 'article';
        $contentsArray['id'] = $id;

        $contentsArray['categories'] = $category->getCatsHierarchy();
        $contentsArray['photos'] = $obj->getPhotos($id);
        $contentsArray['attachments'] = $obj->getAttachments($id);
        $contentsArray['related'] = $obj->loadRelated($id);

        $contentsArray['articles'] = $obj->getAll();
        $this->template = 'content/frm-article.tpl';

        return $contentsArray;
    }

    public function save()
    {
        $obj = new cArticle();
        $contentsArray = array('contents' => $obj->save($_POST));
        return $contentsArray;
    }

    public function delete()
    {
        $id = Request::parseUrl(3);

        $obj = new cArticle();
        $contentsArray = array('contents' => $obj->delete($id));
        return $contentsArray;
    }
    
}