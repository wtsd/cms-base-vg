<?php
namespace wtsd\cli;
/**
*
* @usage: 
*     $parser = new \wtsd\cli\Parser();
*     $xml = $parser->receivePage($url, $additional, $is_json);
*
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Parser
{
	protected $url = '';
	protected $_cookieFile = '';
    protected $format = 'html';

	public function __construct($url = '')
	{
		$this->url = $url;
		$this->_cookieFile = ROOT . '/temp/cookies/cookie_' . date('now') . '.txt';
	}

    protected function getCurlHeaders()
    {
        $headers = array(
            'Origin: ' . $this->url,
            'Accept-Encoding: gzip,deflate,sdch',
            //'Host: example.com',
            'Accept-Language: en-US,en;q=0.8,ru;q=0.6',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36',
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Cache-Control: max-age=0',
            //'Referer: http://example.com/referer/?id=XXXX',
            'Connection: keep-alive',
         );
        return $headers;
    }

    protected function getCurlOptions($url)
    {
        $options = array(
                CURLOPT_URL => $url,
                CURLOPT_COOKIEJAR => $this->_cookieFile,
                CURLOPT_COOKIEFILE => $this->_cookieFile,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => $this->getCurlHeaders(),
                CURLOPT_SSL_VERIFYPEER => false,
            );
        return $options;
    }

    public function downloadPage($url, $additional_options = null)
    {
        $curl = curl_init();
        $options = $this->getCurlOptions($url);
        if ($additional_options !== null) {
            foreach ($additional_options as $option => $value) {
                $options[$option] = $value;
            }
        }
        curl_setopt_array($curl, $options);
        $data = curl_exec($curl);
        curl_close($curl);
        
        $encoded = @gzdecode($data);
        if ($encoded) {
            $data = $encoded;
        }
        
        return $data;
    }


    public function receivePage($url, $additional_options = null, $isJson = false)
    {
        $data = $this->downloadPage($url, $additional_options);
        if ($data === false) {
            echo ">>>> Error downloading!".PHP_EOL;
            return null;
        }
        
        if ($isJson || $this->format == 'json') {
	        $json = json_decode($data);
	        return $json;
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        libxml_clear_errors();
        /*if (!$dom) {
            //throw new DOMException('Document parsing error');
        }*/

        $xml = simplexml_import_dom($dom);


        return $xml;
    }

    public function eprint($message, $type = 1)
    {
		echo date('Y-m-d H:i:s')." PARSER: " . $message . PHP_EOL;
    }

    public function setCookieData($data)
    {
        echo sprintf("Writing cookie info to %s".PHP_EOL, $this->_cookieFile);
        $fh = fopen($this->_cookieFile, 'w');
        fwrite($fh, $data);
        fclose($fh);
    }
}