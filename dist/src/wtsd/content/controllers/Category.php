<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Request;
use wtsd\common\Register;
use wtsd\content\Category as cCategory;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Category extends Controller
{
    protected $_category = null;
    protected $template = 'category.tpl';

    public function run()
    {
        $rewrite = Request::parseUrl(1);
        $page = intval(Request::parseUrl(2));
        return $this->view($rewrite, $page);
    }
    
    public function view($rewrite, $page = 1)
    {
        $page = intval($page);
        $this->_category = new cCategory($rewrite);
        if ($this->_category->isRouted()) {
            $redirectUrl = Request::parseUrl();
            if ($redirectUrl) {
                $this->code = 302;
                $this->redirectUrl = $redirectUrl;
            }
        }
        // @todo: Check if there is a special routing for the page and redirect there
        if (mb_strlen($rewrite) > 0) {
            $page = ($page === 0) ? 1 : $page;
            $contentsArray = $this->_category->buildContents($rewrite, $page);
            if (isset($contentsArray['error'])) {
                $this->code = 404;
                return $contentsArray;
            }
        } else {
            $this->code = 302;
            return null;
        }

        return $contentsArray;
    }

    protected function setMeta()
    {
        $meta = Register::get('lang', 'meta');

        $meta['keywords'] .= $this->_category->name;
        $meta['description'] .= $this->_category->name . ' ' . mb_substr(strip_tags($this->_category->lead), 0, 50, 'utf-8');

        return $meta;
    }

    public function redirectFromId()
    {
        $category = new \wtsd\content\Category();
        $id = Request::parseUrl(1);
        $row = $category->getById($id);
        //die(var_dump($row));
        $newUrl = '/category/'.$row['rewrite'].'/';
        $this->code = 302;
        $this->redirectUrl = $newUrl;
        return [];
    }

}
