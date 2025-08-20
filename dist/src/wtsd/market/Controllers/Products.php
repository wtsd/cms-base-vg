<?php
namespace wtsd\market\controllers;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\Register;
use wtsd\common\Controller;
use wtsd\market\Offer;
use wtsd\market\PCategory;
use wtsd\market\PSpec;
use wtsd\market\Vendor;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Products extends Controller
{

    public function run()
    {
        
        $rewrite = Request::parseUrl(1);
        $page = (Request::parseUrl(2) == 0) ? 1 : Request::parseUrl(2);

        return $this->view($rewrite, $page);

    }

    protected function view($rewrite = null, $page = 1)
    {
        $config = Register::get('config');

        $filters = $this->prepareFilters();
        $perPage = $config['catalogue']['perpage'];
        $off = intval(($page - 1) * $perPage);
        $pcategory = new PCategory($rewrite);

        $contents = array(
            'preUrl'=> '/products/' . $rewrite . (($rewrite !== '') ? '/' : ''),
        );


        $offer = new Offer();
        $contents['pcategories'] = $pcategory->getPCats(0);
        $contents['rewrite'] = $rewrite;
        $contents['currentCategory'] = $pcategory->fromRewrite($rewrite);
        $contents['offers'] = $offer->getTop($rewrite, $perPage, $off);
        $contents['offers_cnt'] = $offer->getCount($rewrite);
        $contents['pages'] = ceil($contents['offers_cnt'] / $perPage);
        $contents['specs'] = PSpec::getSpecsByPCat($pcategory->getId());
        
        $vendor = new Vendor();
        $contents['vendors'] = $vendor->getAll();
        $contents['curPage'] = $page;

        $contents['breadcrumb_arr'] = $pcategory->getParentsArray();

        $category = new \wtsd\content\Category();
        $products = $category->buildContents('products');
        if (isset($products['row'])) {
            $contents['obj'] = $products['row'];
        } else {
            $contents['obj'] = [];
        }
        
        $this->template = 'products.tpl';
        return $contents;
    }
 
    protected function prepareFilters()
    {
        if (Request::getGet('filter')) {
            
        }
    }

}
