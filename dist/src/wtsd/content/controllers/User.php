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
class User extends Controller
{
    protected $template = 'user.tpl';

    public function run($tag = '')
    {
        $user_id = Request::parseUrl(1);
        $page = Request::parseUrl(2);

        $page = intval($page) == 0 ? 1 : intval($page);

        $perpage = 10;

        $article = new \wtsd\content\Article();
        $contents = [];
        
        $contents['user'] = \wtsd\common\Factory::create('User')->getFromId($user_id);
        $contents['articles'] = $article->getByUser($user_id, $page, $perpage);
        $contents['postscount']= $article->getByUserCount($user_id);

        $contents['preUrl'] = sprintf('/user/%s/', $tag);
        $contents['curPage'] = $page;
        $contents['pages'] = ceil($contents['postscount'] / $perpage) + 1;

        return $contents;
        
    }
    
}
