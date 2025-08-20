<?php
namespace wtsd\misc\Controllers\Api;

use wtsd\common\Controllers\Api;
use wtsd\common\Request;
use wtsd\misc\Feedback as cFeedback;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Feedback extends Api
{

    public function send()
    {
        $this->code = 200;

        $action = Request::getPost('act');
        $result = array('status' => 'error');
        $token = Request::getPost('token');
        $name = Request::getPost('name');
        $email = Request::getPost('email');
        $msg = Request::getPost('msg');

        if ($token) {
            $feedback = new cFeedback();
            $result = $feedback->doSend($token, $name, $email, $msg);
        }
        
        return $result;
    }
    
}
