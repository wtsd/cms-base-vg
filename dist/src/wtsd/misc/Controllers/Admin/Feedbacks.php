<?php
namespace wtsd\misc\Controllers\Admin;

use wtsd\common;
use wtsd\common\AdminController;
use wtsd\common\Request;
use wtsd\misc\Feedback;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Feedbacks extends AdminController
{   
    public function run()
    {
        $offset = Request::parseUrl(2);

        $feedback = new Feedback();
        $contentsArray = array('contents' => 'Feedbacks here', 'records' => $feedback->getList($offset), 'fields' => $feedback->getFields());

        $this->template = 'feedbacks.tpl';
        return $contentsArray;
    }
}