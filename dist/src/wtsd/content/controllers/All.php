<?php
namespace wtsd\content\controllers;

use wtsd\common\Controller;
use wtsd\common\Request;
use wtsd\content\Article;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class All extends Controller
{

    protected $template = 'all-posts.tpl';

    public function run()
    {

        $page = Request::parseUrl(1);
        $page = intval($page) == 0 ? 1 : intval($page);
        $article = new Article();

        $perpage = 10;

        $contents['posts'] = $article->getTopArticles($perpage, $page);
        $contents['postscount'] = $article->getAllCount();
        $contents['pages'] = ceil($contents['postscount'] / $perpage) + 1;
        $contents['curPage'] = $page;
        $contents['preUrl'] = '/all/';

        return $contents;
    }
    
}
