<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\content\Category;
use wtsd\content\Article;
use wtsd\market\PCategory;
use wtsd\market\Offer;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Search extends Controller
{
    protected $template = 'search.tpl';

    public function run()
    {
        if (Request::getGet('query')) {
            $query = Request::getGet('query');
        } else {
            $query = Request::parseUrl(1);
        }

        $page = 1;
        if (intval(Request::parseUrl(2)) > 1) {
            $page = intval(Request::parseUrl(2));
        }

        if (mb_strlen($query) > 0) {
            return $this->search($query, $page);
        } else {
            return $this->form();
            
        }

    }
    
    public function search($query = '', $page = 1)
    {
        $contents = [];
        $contents['type'] = 'results';
        $contents['query'] = $query;
        if (mb_strlen($query) > 3) {
            // Seach categories
            $contents['categories'] = Category::doSearch($query, $page);
            // Seach articles
            $contents['articles'] = Article::doSearch($query, $page);
            // Seach pcategories
            $contents['pcategories'] = PCategory::doSearch($query, $page);
            // Seach offers
            $offer = new Offer();
            $contents['offers'] = $offer->find($query, 10, $page);

        } else {
            $contents['contents'] = 'Query is too short!';
        }
        return $contents;
    }

    public function form()
    {
        return array('type' => 'frm');
    }
}

