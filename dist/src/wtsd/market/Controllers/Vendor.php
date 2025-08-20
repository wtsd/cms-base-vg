<?php
namespace wtsd\market\controllers;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\Register;
use wtsd\market\Vendor as CVendor;
use wtsd\common\Controller;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Vendor extends Controller
{
    protected $vendor = null;

    public function run()
    {
        $rewrite = Request::parseUrl(1);
        return $this->view($rewrite);
        
    }

    public function view($rewrite = null)
    {
        if ($rewrite) {
            $vendor = new CVendor();
            $offer = new \wtsd\market\Offer();
            $this->vendor = $vendor->loadFromRewrite($rewrite);
            $contents['vendor'] = $this->vendor;
            $contents['offers'] = $offer->getByVendor($this->vendor['id']);
            $this->template = 'vendor.tpl';

            return $contents;
        } else {
            $this->code = 302;
            $this->redirectUrl = '/products/';

            return [];
        }
    }

    protected function setMeta()
    {
        $meta = Register::get('lang', 'meta');

        /*
        $meta['keywords'] .= $this->_vendor->name;
        $meta['description'] .= $this->_vendor->name . ' ' . mb_substr(strip_tags($this->_vendor->descr), 0, 50, 'utf-8');
        */
        return $meta;
    }

    
}
