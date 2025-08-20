<?php
namespace wtsd\common\Controllers\General;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class NotFound extends Controller
{

	//protected $templateDir = 'resources/views/admin';

    public function run()
    {
    	if (Request::parseUrl(0) == 'adm') {
    		$this->setTemplateDir('resources/views/admin');
    	}
        $contentsArray = [];
        $contentsArray['url'] = Request::parseUrl();
        $this->code = 404;
        $this->template = '404.tpl';

        return $contentsArray;
    }

}
