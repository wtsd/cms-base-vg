<?php
namespace wtsd\misc\Controllers\General;

use wtsd\common;
use wtsd\common\Controller;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Donate extends Controller
{
    protected $template = 'donate.tpl';

    public function run($status = '')
    {
        return $this->form();
    }


    public function success()
    {
        $this->template = 'donate-success.tpl';
        return [];
    }
    public function form()
    {
        $yandex = \wtsd\common\Register::get('yandex');

        $this->template = 'donate.tpl';

        $contents['yandex'] = $yandex;

        return $contents;
    }

}