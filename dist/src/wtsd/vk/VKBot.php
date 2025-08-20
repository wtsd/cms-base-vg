<?php
namespace wtsd\vk;

use wtsd\common;
use wtsd\common\Database;

class VKBot
{
    const SQL_LOAD_CRED_ID = "SELECT * FROM `tblCredentials` WHERE `status` = 'active' AND `id` = :id LIMIT 1",
            SQL_LOAD_RAND = "SELECT * FROM `tblCredentials` WHERE `status` = 'active' ORDER BY rand() LIMIT 1";

    protected $_uid;
    protected $_tel;
    protected $_email;
    protected $_password;
    protected $_uri;

    protected $_secretKey = '';
    protected $_cookieFile = '';
    protected $_cookieContents = '';

    protected $_appId = '';

    protected $_curl = null;


    public function __construct($appId, $secretKey, $load = true, $id = null)
    {
        
        $this->_appId = $appId;
        $this->_secretKey = $secretKey;

        if ($load) {
            $this->_loadCredentials($id);
            $this->_cookieFile = ROOT . '/temp/cookies/cookie_' . $this->_email . '.txt';
        }
    }

    protected function _loadCredentials($id = null)
    {
        $DBH = Database::getInstance();
        if ($id) {
            $placeholders = array(':id' => intval($id));
            $row = Database::selectQuery(self::SQL_LOAD_CRED_ID, $placeholders, true);
        } else {
            $row = Database::selectQuery(self::SQL_LOAD_RAND, null, true);
        }

        if ($row) {
            $this->setCredentials($row['tel'], $row['email'], $row['password']);
        }
    }

    public function setCredentials($tel, $email, $password)
    {
        $this->_tel = $tel;
        $this->_email = $email;
        $this->_password = $password;
        $this->_cookieFile = ROOT . '/temp/cookies/cookie_' . $this->_email . '.txt';

    }

    public function isAuthorized()
    { 
        $feed_url = 'https://vk.com/feed';
        $xml = $this->_recievePage($feed_url);
        $isAuthorized = preg_match("/logout/uis", $xml->asXML());

        /* Setting id */
        if ($isAuthorized) {
            $arr = $xml->xpath("//a[@id='head_music']");
            $strArr = explode('?', $arr[0]['href']);
            $id = str_replace('/audios', '', $strArr[0]);
            $this->_uid = $id;
        }

        return $isAuthorized;
    }

    public function doAuthorize()
    {
        $base_url = 'https://vk.com/';
        $xml = $this->_recievePage($base_url);
        $arr = $xml->xpath("//input[@name='ip_h']");
        $ip_h = $arr[0]['value'];

        $postfields = sprintf('act=login&role=al_frame&expire=&captcha_sid=&captcha_key=&_origin=%s&ip_h=%s&email=%s&pass=%s', urlencode($base_url), $ip_h, urlencode($this->_email), urlencode($this->_password));
        $data = $this->_doPost('https://login.vk.com/?act=login', $postfields);

        preg_match('/onLoginDone\(\'[\s\S]*?\'/uis', $data, $t_matches);
        $this->_uri = @str_replace("'", "", str_replace('onLoginDone(\'/', '', $t_matches[0]));

        if ($this->_uri) {
            $url = 'https://vk.com/' . $this->_uri;
            echo "Going to $url\n";

            $myPageXML = $this->_recievePage($url);
            $codeQuest = $myPageXML->xpath("//input[@id='code']");
            if ($codeQuest) {
                $prePhone = $myPageXML->xpath("//div[@class='label ta_r']");
                $postPhone = $myPageXML->xpath("//span[@class='phone_postfix']");
                die($prePhone[0] . 'xxxx' . $postPhone[0]);
                // @todo: Send phone request
                // <div class="label ta_r">+7</div>
                // <td width="1000px"><span class="phone_postfix">&nbsp;17</span></td>

                // @todo: Check phone number
                /*
                $obj = $myPageXML->xpath("//input[@name='ip_h']");
                $ip_h = $obj[0]['value'];
                $code = '7000031';
                preg_match("/hash: '(.*)'};/uis", $myPageXML->asXML(), $matches);
                die(var_dump($matches));
                $hash = $matches[0];


                $data = sprintf('al=1&al_page=3&code=%s&hash=%s&to='. $code, $hash);
                die("Data: $data\n");
                */
                die('Needs phone checking.');

            } else {
                //die($myPage->asXML());
                //echo '<div class="debug">Successfully authorized!</div>';
                return true;
            }
        } else {
            die(var_dump($data));
        }

    }

    public function doLike($wallId, $type = 'wall', $isRepost = false, $msg = '')
    {
        if ($isRepost) {
            $postfields_get = sprintf('act=a_get_stats&al=1&object=wall%s', $wallId);
            $result_get = $this->_doPost('https://vk.com/like.php', $postfields_get);

            $regex = "/likeShare\('" . $wallId . "', '(.*)'\)/i";
            preg_match($regex, $result_get, $matches);
            $hash = $matches[1];

            $postfields = sprintf('Message=%s&act=a_do_publish&al=1&from=box&hash=%s&list=&object=wall%s&to=0', $msg, $hash, $wallId);
        } else {
            if ($type == 'wall') {
                $url = 'https://vk.com/wall' . $wallId;
                $xml = $this->_recievePage($url);
                $divs = $xml->xpath("//div[@class='fw_post_info']//div[@class='fw_like_wrap fl_l']");
                $arr = explode("'", $divs[0]['onclick']);
                $hash = $arr[3];

                $postfields = sprintf('act=a_do_like&al=1&from=wkview&hash=%s&object=%s%s', $hash, $type, $wallId);
            } elseif ($type == 'photo') {
                $url = 'https://vk.com/photo' . $wallId;
                $xml = $this->_recievePage($url);
                die($xml->asXML());
                $regexHash = '/"hash":"([0-9a-z]*)","x_src/';
                preg_match($regexHash, $xml->asXML(), $matches);
                die(var_dump($matches));
                $hash = $matches[1];

                $postfields = sprintf('act=a_do_like&al=1&from=photo_viewer&hash=%s&object=%s%s', $hash, $type, $wallId);
            }

            /*
            if ($this->checkCaptcha($xml)) {
                die('Needs captcha');
            }
            */

        }

        $result = $this->_doPost('https://vk.com/like.php', $postfields);
        //echo 'Liked a post ' . $wallId . "\n";
        //die(var_dump($result));
    }

    public function doRepost($wallId, $type = 'wall', $msg = '')
    {
        $this->doLike($wallId, $type, true, $msg);
    }

    public function sendIm($recipientId, $msg)
    {
        /*
        // Old code
        $urlIm = 'https://vk.com/im?media=&sel=' . $recipientId;
        $xml = $this->_recievePage($urlIm);
        $arr = $xml->xpath("//div[@class='dialogs_del']");
        $arr_str = explode("'", $arr[0]['onclick']);
        $hash = $arr_str[1];

        $postfields = sprintf('act=a_send&al=1&hash=%s&media=&msg=%s&title=&to=%s&ts=%s', $hash, urlencode($msg), $recipientId, $this->_uid);
        */

        $urlIm = 'https://vk.com/write' . $recipientId;
        $xml = $this->_recievePage($urlIm);
        $regex = '/"writeHash":\"(.*)",/i';
        preg_match($regex, $xml->asXML(), $matches);
        $arr = explode('"', $matches[1]);
        $hash = $arr[0];

        $postfields = sprintf('act=a_send&al=1&chas=%s&from=im&media=&message=%s&title=&to_ids=%s', $hash, urlencode($msg), $recipientId);

        $result = $this->_doPost('https://vk.com/al_mail.php', $postfields);

        //var_dump($result);
    }

    public function getPosts($pageId, $offset = 0, $is_group = false)
    {
        $urls = [];
        if ($offset > 0) {
            // Profile
            if (!$is_group) {
                if (!is_numeric($pageId)) {
                    $apiArr = $this->apiJS('users.get', array('user_ids' => $pageId));
                    $pageId = $apiArr->response[0]->uid;
                }
                $postfields = sprintf('act=get_wall&al=1&fixed=&offset=%d&owner_id=%s&type=own', $offset, $pageId);
                $data = $this->_doPost('https://vk.com/al_wall.php', $postfields);
                $regex = '/href="\/wall([0-9_]*)\"/i';

            } else {
                if (!is_numeric($pageId)) {
                    $apiArr = $this->apiJS('groups.getById', array('group_ids' => $pageId));
                    $pageId = $apiArr->response[0]->gid;
                }
                // Group
                $postfields = sprintf('act=get_wall&al=1&fixed=&offset=%s&owner_id=-%s&type=own', $offset, $pageId);
                //die("postfields: $postfields\n");
                $data = $this->_doPost('https://vk.com/al_wall.php', $postfields);
                $regex = '/href="\/wall-([0-9_]*)\"/i';
            }

            preg_match_all($regex, $data, $matches);
            $urls = $matches[1];

        } else {
            $xml = $this->_recievePage('https://vk.com/' . $pageId);
            $arr = $xml->xpath("//div[@class='reply_link_wrap sm']/small/a[contains(@href, '/wall')]");
            foreach ($arr as $link) {
                $urls[] = str_replace('wall', '', trim($link['href'], '/'));
            }

        }

        return $urls;
    }

    public function getLikers($postId, $type = 'like')
    {
        $offset = 0; // each step is 60
        if ($type == 'like') {
            $perpage = 60;
            $getType = 'likes';
            $xpath_users = "//div//a[@class='wk_likes_liker_ph']";
        } else {
            $perpage = 4;
            $getType = 'shares';
            $xpath_users = "//div//a[@class='post_image']";
        }
        $likers = [];


        $headers = $this->_getCurlHeaders();
        do {
            $postfields = 'act=show&al=1&offset=' . $offset . '&w=' . $getType . '%2Fwall-' . $postId;
            $xml = $this->_doPost('https://vk.com/wkview.php', $postfields);

            if ($offset == 0) {
                $summary = $xml->xpath("//div[@class='summary_tab3']/nobr");
                $liked_arr = explode(' ', trim($summary[0]));
                $reposted_arr = explode(' ', trim($summary[1]));

                $liked = $liked_arr[0];
                $reposted = $reposted_arr[0];
            }
            //echo $xml->asXML();
            $likers_xml = $xml->xpath($xpath_users);

            foreach ($likers_xml as $liker_xml) {
                $likers[] = trim($liker_xml['href']);
            }
            $offset += $perpage;
        } while ($liked > $offset);
        $likers = array_unique($likers);

        $result = array(
            'liked' => intval($liked),
            'reposted' => intval($reposted),
            'users' => $likers,
            );
        return $result;
    }

    static public function validateWallId($wallId)
    {
        return ($wallId && preg_match("/[0-9]_[0-9]/uis", $wallId));
    }

    protected function checkCaptcha($xml)
    {
        $captchas = $xml->xpath("//div[@class='captcha']//img");
        if (count($captchas) > 0) {
            return true;
        } else {
            return false;
        }
    }
    /**
     *
     */
    public function api($method, $params = null, $url = '')
    {
        sleep(1);
        if (!$params) {
            $params = []; 
        }

        $params['api_id'] = $this->_appId;
        $params['v'] = '3.0';
        $params['test_mode'] = '1';
        $params['method'] = $method;

        ksort($params);
        $sig = '';
        foreach ($params as $k => $v) {
            $sig .= $k . '=' . $v;
        }
        $sig .= $this->_secretKey;
        $params['sig'] = md5($sig);

        $pice = [];
        foreach ($params as $k => $v) {
            $pice[] = $k . '=' . urlencode($v);
        }

        $post_fields = implode('&', $pice);
        //$referer = 'http://vk.com/app' . $this->_appId . '_' . $this->_appId;

        $data = simplexml_load_string($this->_doPost('https://api.vk.com/api.php', $post_fields));

        return $data;
    }

    public function apiJS($method, $params = null, $url = 'https://api.vk.com/method/')
    {
        sleep(1);
        if (!$params) {
            $params = []; 
        }

        $args = [];
        foreach ($params as $key => $value) {
            $args[] = $key . '=' . $value;
        }
        
        $urlFinal = $url . $method . '?' . implode('&', $args);
        $res = $this->_downloadPage($urlFinal);
        $result = json_decode($res);
        
        return $result;
    }

    protected function _getCurlHeaders()
    {
        $headers = array(
            //'Origin: http://vk.com',
            'Accept-Encoding: gzip,deflate,sdch',
            //'Host: login.vk.com',
            'Accept-Language: en-US,en;q=0.8,ru;q=0.6',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36',
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Cache-Control: max-age=0',
            //'Referer: http://vk.com/login?act=mobile&hash=7f0b0905d6180725',
            'Connection: keep-alive',
         );
        return $headers;
    }

    protected function _getCurlOptions($url)
    {
        $options = array(
                CURLOPT_URL => $url,
                CURLOPT_COOKIEJAR => $this->_cookieFile,
                CURLOPT_COOKIEFILE => $this->_cookieFile,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => $this->_getCurlHeaders()
            );
        return $options;
    }

    protected function _downloadPage($url, $additional_options = null)
    {
        $this->_curl = curl_init();
        $options = $this->_getCurlOptions($url);
        if ($additional_options !== null) {
            foreach ($additional_options as $option => $value) {
                $options[$option] = $value;
            }
        }

        foreach ($options as $option => $value) {
            curl_setopt($this->_curl, $option, $value);
        }

        $data = curl_exec($this->_curl);
        if (!$data) {
            die('No data received!');
            //throw new Exception("CURL didn't recieve the page");
        }
        curl_close($this->_curl);
        if ($encoded = @gzdecode($data)) {
            $data = $encoded;
        }
        return $data;
    }

    protected function _doPost($url, $postfields)
    {
        $additional_options = array(
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => 
                array(
                    'Origin: https://vk.com',
                    'Content-Type: application/x-www-form-urlencoded',
                    'Accept: */*',
                    'Referer: ' . $url,
                    'X-Requested-With: XMLHttpRequest',
                    'Content-Length: ' . strlen($postfields),
                )
            );
        $result = $this->_downloadPage($url, $additional_options);
        return $result;
    }


    protected function _recievePage($url, $additional_options = null)
    {
        $data = $this->_downloadPage($url, $additional_options);

        /*
        $json = json_decode($data);
        if ($json) {
            $data = $json;
        }
        */


        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        libxml_clear_errors();
        if (!$dom) {
            die('Parsing error!');
            //throw new DOMException('Document parsing error');
        }

        $xml = simplexml_import_dom($dom);

        return $xml;
    }

}
