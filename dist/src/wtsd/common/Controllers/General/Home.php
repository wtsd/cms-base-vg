<?php
namespace wtsd\common\Controllers\General;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Register;
use wtsd\content\Category;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Home extends Controller
{

    protected $template = 'home.tpl';

    public function run()
    {

        $category = new Category();

        $contents = $category->buildContents(\wtsd\common\Register::get('config', 'default_cat'));
        
        $page = \wtsd\common\Request::parseUrl(1);
        $page = intval($page) == 0 ? 1 : intval($page);
        $article = new \wtsd\content\Article();

        $perpage = Register::get('config', 'perpage');
        $defaultCatRewrite = \wtsd\common\Register::get('config', 'default_cat');
        $category = new \wtsd\content\Category();
        $contents['posts'] = [];

        $contents['row'] = $category->buildContents($defaultCatRewrite);
        if (isset($contents['row']['id'])) {
            $contents['posts'] = $article->getByCategory($contents['row']['row']['id'], $page, $perpage);
        }

        $contents['postscount'] = $article->getAllCount();
        $contents['pages'] = ceil($contents['postscount'] / $perpage);
        $contents['curPage'] = $page;
        $contents['preUrl'] = '/all/';
        $contents['count'] = $article->countBy('all');


        if (Register::get('config', 'market')) {
            $offer = new \wtsd\market\Offer();
            if (Register::get('config', 'offers_special')) {
                $contents['specialProducts'] = $offer->getSpecial(9);
            }

            if (Register::get('config', 'offers_latest')) {
                $contents['latestProducts'] = $offer->getTop('', 5);
            }
        }

        return $contents;
    }
    
}
