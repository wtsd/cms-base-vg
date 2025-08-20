<?php
namespace wtsd\misc\Controllers\Api;

use wtsd\common\Controllers\Api;
use wtsd\common\Request;
use wtsd\market\Offer;

/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Comment extends Api
{

    public function run()
    {
        $action = Request::parseUrl(2);
        $config = \wtsd\common\Register::get('config');
        $labels = \wtsd\common\Register::get('lang');

        $result = [];
        if ($action == 'add') {
            $comment = Request::getPost('comment');
            $name = Request::getPost('name');
            $type = Request::getPost('type');
            $fid = Request::getPost('fid');
            $ip = $_SERVER['REMOTE_ADDR'];
            $page = 1;
            if ($config['is_captcha']) {
                $gRecaptchaResponse = Request::getPost('g-recaptcha-response');
            }
            if ($name == '' || $comment == '') {
                $result['status'] = 'error';
                $result['msg'] = $labels['comments']['required'];
                return $result;
            }


            if ($config['is_captcha']) {
                if (!\wtsd\misc\Comment::checkCaptcha($gRecaptchaResponse, $ip)) {
                    $result['status'] = 'error';
                    $result['msg'] = $resp->getErrorCodes();
                    return $result;
                }
            }

            if ($type == 'offer') {
                $offer = new \wtsd\market\Offer();
                $cmntId = $offer->saveComment($comment, $name, $fid, $ip);

                if ($cmntId) {
                    $result['msg'] = $labels['comments']['success'];
                    $result['status'] = 'ok';
                    $result['cmnt_id'] = $cmntId;

                    $result['html'] = \wtsd\misc\Comment::renderPage($type, $fid, $page, 10);
                    $result['count'] = \wtsd\misc\Comment::count($fid, $type);
                }
            }

            if ($type == 'article') {
                $article = new \wtsd\content\Article();
                $cmntId = $article->saveComment($comment, $name, $fid, $ip);

                if ($cmntId) {
                    $result['msg'] = $labels['comments']['success'];
                    $result['status'] = 'ok';
                    $result['cmnt_id'] = $cmntId;

                    $result['html'] = \wtsd\misc\Comment::renderPage($type, $fid, $page, 10);
                    $result['count'] = \wtsd\misc\Comment::count($fid, $type);
                }
            }
        }

        if ($action == 'get') {
            $type = Request::getPost('type');
            $page = Request::getPost('page');
            $fid = Request::getPost('fid');
            
            if ($type == 'offer') {
                $offer = new \wtsd\market\Offer();
                
                $result['html'] = \wtsd\misc\Comment::renderPage($type, $fid, $page, 10);

                $result['status'] = 'ok';
            }
        }
        
        return $result;
    }
    
}
