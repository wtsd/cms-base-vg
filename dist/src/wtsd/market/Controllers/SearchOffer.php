<?php
namespace wtsd\market\controllers;

use wtsd\common\Controller;
use wtsd\common\Request;
use wtsd\market\Offer;
use wtsd\market\PCategory;
/**
 * Created by JetBrains PhpStorm.
 * User: wtsd
 * Date: 6/23/13
 * Time: 4:14 PM
 * To change this template use File | Settings | File Templates.
 */
class SearchOffer extends Controller
{

    protected $template = 'search-offer.tpl';
    public function run()
    {
        $page = (Request::parseUrl(2) == 0) ? 1 : Request::parseUrl(2);
        $query = trim(\wtsd\common\Request::getGet('q'));

        $contents = [];
        $contents['query'] = htmlspecialchars($query, ENT_COMPAT, 'UTF-8');

        $pcategory = new PCategory();
        $contents['pcategories'] = $pcategory->getPCats();
        $contents['rewrite'] = '';

        if ($query != '') {

            $offer = new \wtsd\market\Offer();
            if (is_numeric($query)) {
                $ofr = $offer->loadById($query);

                $this->redirectUrl = '/offer/'.$ofr['rewrite'].'/';
                $this->code = 302;
                return $contents;
            }
            
            $config = \wtsd\common\Register::get('config');
            $perPage = $config['catalogue']['perpage'];
            $off = intval(($page - 1) * $perPage);
            $contents['offers'] = $offer->find($query, $perPage, $off);
            $contents['pages'] = $offer->findPages($query);
            $contents['curPage'] = $page;
            $contents['preUrl'] = '/search-offer/';
        }

        return $contents;
    }

}
