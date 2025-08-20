<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\content\Article as cArticle;
use wtsd\common\Request;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Article extends Controller
{
    protected $template = 'article.tpl';

    public function run()
    {
        $rewrite = Request::parseUrl(1);
        return $this->view($rewrite);
    }

    public function redirect($rewrite)
    {
        $this->code = 302;
        $this->redirectUrl = sprintf('/article/%s/', $rewrite);
        return [];
    }
    
    public function view($rewrite)
    {
        $isDraft = Request::getGet('isDebug');

        $contents = [];
        if (mb_strlen($rewrite) > 0) {
            $article = new \wtsd\content\Article();
            $contents['obj'] = $article->getContents($rewrite, $isDraft);

            if ($isDraft && ($contents['obj']['status'] == 0)) {
                $this->code = 302;
                $this->redirectUrl = sprintf('/article/%s/', $rewrite);
                return [];
            }
            if (!isset($contents['obj']['id'])) {
                $this->code = 302;
                $this->redirectUrl = '/';
                return [];
            }
            
            $contents['prev'] = $article->getPrev($rewrite, $isDraft);
            $contents['next'] = $article->getNext($rewrite, $isDraft);

            $category = new \wtsd\content\Category();
            $contents['currentCat'] = $category->getById($contents['obj']['cat_id'])['rewrite'];

            if (\wtsd\common\AppKernel::getEnvironment() == 'DEV') {
                $contents['recaptcha'] = Register::get('google-dev', 'recaptcha');
            } else {
                $contents['recaptcha'] = Register::get('google', 'recaptcha');
            }


            $article->addViews($contents['obj']['id']);
        } else {
            $this->code = 302;
            $this->redirectUrl = '/';
            return [];
        }
        
        if (isset($contents['error'])) {
            $this->code = 404;
        }

        return $contents;
    }
    

}
