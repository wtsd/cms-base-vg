<?php
namespace wtsd\misc\Controllers\General;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\Register;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Callrequest extends Controller
{
    protected $template = 'call-request.tpl';

    public function run($status = '')
    {
        $contents = array('status' => 'info');
        
        if (\wtsd\common\Request::getPost('name')) {
            $name = \wtsd\common\Request::getPost('name');
            $tel = \wtsd\common\Request::getPost('phone');
            $ip = $_SERVER['REMOTE_ADDR'];

            if (\wtsd\misc\Feedback::requestCall($name, $tel, $ip)) {
                $this->code = 302;
                $this->redirectUrl = '/callrequest/success/';
            } else {

            }
        }

        return $contents;
    }

    public function success()
    {
        return array('status' => 'success');
    }
    
}
