<?php
namespace wtsd\misc\Controllers\General;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\misc\Feedback as cFeedback;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Feedback extends Controller
{
    protected $_cart = null;
    protected $template = 'feedback.tpl';

    public function run()
    {
        $fb = new cFeedback();

        return $fb->getForm();
    }

}