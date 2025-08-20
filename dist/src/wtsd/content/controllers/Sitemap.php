<?php
namespace wtsd\content\controllers;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\content\Category;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Sitemap extends Controller
{

    public function run()
    {
        $contentsArray = [];

        $config = Register::get('config');
        $contentsArray['domain'] = $config['base_url'];
        $contentsArray['urls'] = Category::getMenu();
        $this->template = 'sitemap.tpl';
        $this->format = 'xml';
        
        return $contentsArray;
    }
    
}
