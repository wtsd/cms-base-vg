<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Register;
use wtsd\common\RSS as cRSS;
use wtsd\content\Article;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Rss extends Controller
{

    protected $template = 'rss.tpl';
    protected $format = 'xml';

    public function run()
    {

        return $this->generate();
        
    }
    
    protected function generate()
    {
        $article = new Article();
        $arr = array(
            'contents' => array("pdate" => date("r"), "ldate" => date("r")),
            'labels' => Register::get('lang'),
            'items' => $article->getFeed()
            );
        return $arr;

    }

}
