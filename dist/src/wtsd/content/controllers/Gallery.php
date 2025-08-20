<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\Controller;
use wtsd\content\Gallery as cGallery;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Gallery extends Controller
{
    protected $template = 'gallery.tpl';

    public function run()
    {
        
        $rewrite = Request::parseUrl(1);
        $page = intval(Request::parseUrl(2));

        if ($page == 0) {
            $page = 1;
        }
        
        if ($rewrite !== '') {
            $contents = $this->view($rewrite, $page);
        } else {
            $contents = $this->showAll();
        }

        return $contents;
    }

    public function view($rewrite, $page = 1)
    {
        $gallery = new \wtsd\content\Gallery();
        try {
            $gallery = $gallery->newFromRewrite($rewrite);
            $contents = $gallery->buildMain($page);
            $contents['breadcrumbs'] = $gallery->getBreadcrumb($gallery->getId());
        } catch (\Exception $e) {
            $contents = $gallery->allGalleries();
            $contents['message'] = $e->getMessage();
            $contents['breadcrumbs'] = $gallery->getBreadcrumb();
        }
        return $contents;
    }

    public function showAll()
    {
        return \wtsd\content\Gallery::allGalleries();
    }
    
}
