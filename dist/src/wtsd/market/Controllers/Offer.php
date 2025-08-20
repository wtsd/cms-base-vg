<?php
namespace wtsd\market\controllers;

use wtsd\common;
use wtsd\common\Request;
use wtsd\common\Register;
use wtsd\common\Controller;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Offer extends Controller
{

    public function run()
    {
        $rewrite = Request::parseUrl(1);
        return $this->view($rewrite);
        
    }

    public function view($rewrite = null)
    {
        if ($rewrite) {
            $offer = new \wtsd\market\Offer();
            $this->offer = $offer->loadFromRewrite($rewrite);
            if (isset($this->offer['id'])) {
                $contents = array('offer' => $this->offer);
                if (\wtsd\common\AppKernel::getEnvironment() == 'DEV') {
                    $contents['recaptcha'] = Register::get('google-dev', 'recaptcha');
                } else {
                    $contents['recaptcha'] = Register::get('google', 'recaptcha');
                }
                $contents['title'] = $this->offer['name'] . ' - ' . Register::get('lang', 'global_title');

                $this->template = 'offer.tpl';
                return $contents;
            } else {
                $this->code = 404;
                return [];
            }
        } else {
            $this->code = 302;
            $this->redirectUrl = '/products/';
            return [];
        }
    }

}
