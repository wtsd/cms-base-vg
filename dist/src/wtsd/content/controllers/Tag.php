<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Tag extends Controller
{
    protected $template = 'tag.tpl';

    public function run($tag = '')
    {
        $tag = Request::parseUrl(1);
        $page = Request::parseUrl(2);

        $page = intval($page) == 0 ? 1 : intval($page);

        $perpage = 10;

        $article = new \wtsd\content\Article();
        $contents = [];
        $contents['tag'] = urldecode($tag);
        $contents['articles'] = $article->getByTag($tag, $page, $perpage);
        $contents['postscount']= $article->getByTagCount($tag);

        $contents['preUrl'] = sprintf('/tag/%s/', $tag);
        $contents['curPage'] = $page;
        $contents['pages'] = ceil($contents['postscount'] / $perpage) + 1;

        return $contents;
        
    }
    
}
