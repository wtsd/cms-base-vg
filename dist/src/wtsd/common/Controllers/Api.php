<?php
namespace wtsd\common\Controllers;

use wtsd\common\Request;
use wtsd\common\Controller;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Api extends Controller
{
    protected $method = '';
    protected $code = 405;
    protected $format = 'json';

    protected $onlyAuthorized = true;
    protected $contents;

    public function run()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        //header("Content-Type: application/json");

        $this->method = $_SERVER['REQUEST_METHOD'];
        $data = [];
        switch ($this->method) {
            case 'GET':
                $id = Request::parseUrl(3);
                if (intval($id) > 0) {
                    $data = $this->methodGET($id);
                } else {
                    $action = '';
                    if ($id != '') {
                        $action = $id;
                        $arg = Request::parseUrl(4);
                        if (method_exists($this, $action)) {
                            $data = $this->$action($arg);
                        }
                    }
                    $data = $this->methodGET(0);
                }
                break;
            case 'PUT':
                $id = Request::parseUrl(3);
                $args = $_GET;
                $data = $this->methodPUT($id, $args);
                break;
            case 'POST':
                $action = Request::parseUrl(3);
                $args = $_POST;
                if ($action != '' && method_exists($this, $action)) {
                    $data = $this->$action($args);
                } else {
                    $data = $this->methodPOST($args);
                }
                break;
            case 'DELETE':
                $id = Request::parseUrl(3);
                $data = $this->methodDELETE($id);
                break;

            /*
            case 'OPTIONS':
                break;
            case 'HEAD':
                break;
            case 'PATCH':
                break;
            */
            default:
                break;
        }

        return $data;
        //$this->response($data);
    }

    protected function methodGET($id = null, $action = null)
    {
        $obj = $this->getObject();
        if (intval($id) > 0) {
            $row = $obj->load($id);

            if ($row['id'] > 0) {
                $this->code = 200;
            } else {
                $this->code = 404;
            }
            return $row;
        } else {
        
            $page = (isset($_GET['page']) ? $_GET['page'] : 1);
            $size = (isset($_GET['size']) ? $_GET['size'] : 1000);
            $dir = (isset($_GET['dir']) ? $_GET['dir'] : 'desc');
            $propertyName = (isset($_GET['propertyName']) ? $_GET['propertyName'] : 'id');

            $filter = (isset($_GET['filter']) ? $_GET['filter'] : '');

            $action = Request::parseUrl(3);
            if (method_exists($this, $action)) {
                $this->code = 200;
                return $this->{$action}();
            }
            
            $rows = $obj->getAll($page, $size, $filter);

            $offset = (($page - 1) * $size);
            
            header('Content-Range: resources '.($offset).'-'.($offset+$size));

            /*if (count($rows) > 0) {
                $this->code = 200;
            } else {
                $this->code = 404;
            }*/
            $this->code = 200;
            
            if ($action == 'table') {
                for ($i = 0; $i < count($rows); $i++) {
                    $rows[$i]['actions'] = '';
                }
                return ['data' => $rows];
            }
            return $rows;
        }
    }
    
    protected function methodPOST(array $arr)
    {
        $obj = $this->getObject();
        //$placeholders = $this->getPlaceholders($arr);

        $validation = $obj->validate($arr);
        
        if (count($validation) > 0) {
            $this->code = 409;
            return ['errors' => $validation];
        }
        try {
            $id = $obj->save($arr);
            $row = $obj->load($id);
            
            if (isset($row['id']) && $row['id'] > 0) {
                $this->code = 200;
            } else {
                $this->code = 500;
            }
            return $row;
        } catch (\Exception $e) {
            if ($e->getErrorCode() == 1062) {
                $this->code = 409;
                return ['errors' => ['Duplicate entry']];
            }
            return ['error' => $e->getMessage()];
        }

    }

    protected function methodPUT($id, array $arr)
    {
        $arr['id'] = $id;
        if (intval($id) === 0) {
            $this->code = 400;
            return ['errors' => ['Missing id']];
        }
        $obj = $this->getObject();

        $validation = $obj->validate($arr);
        if (count($validation) > 0) {
            $this->code = 409;
            return ['errors' => $validation];
        }
        //$placeholders = $this->getPlaceholders($arr);
        try {
            $id = $obj->save($arr);
            $row = $obj->load($id);
            if ($row['id'] > 0) {
                $this->code = 200;
            } else {
                $this->code = 500;
            }
            return $row;
        } catch (\Exception $e) {
            if ($e->getErrorCode() == 1062) {
                $this->code = 409;
                return ['errors' => ['Duplicate entry']];
            }
        }
    }

    protected function methodDELETE($id = null)
    {
        $obj = $this->getObject();
        $placeholders = [
                    ':id' => $id,
                ];

        try {
            $obj->load($id);
            if ($obj->getId() == 0) {
                $this->code = 404;
                
            } else {
                $data = $obj->delete($id);
                $this->code = 200;
            }
            return [];
        } catch (\Exception $e) {
            $this->code = 404;
            $data = ['errors' => [$e->getMessage()]];
        }
        return $data;
    }



    /*
    abstract function methodHEAD($id = null) {}
    abstract function methodOptions() {}
    abstract function methodPATCH() {}
    */


    protected function getObject()
    {
        //return new \wtsd\shadow\ObjName();
    }

    protected function getPlaceholders(array $arr)
    {
        return [];
    }

    protected function doValidate($placeholders)
    {
        return true;
    }

    protected function validate()
    {
        
    }

    protected function publicRun()
    {
        //return $this->run();
        $this->code = 401;
    }

    protected function filePutResponse()
    {
        $result = [
            "size" => "225.4KB",
            "rev" => "35e97029684fe",
            "thumb_exists" => false,
            "bytes" => 230783,
            "modified" => "Tue, 19 Jul 2011 21:55:38 +0000",
            "path" => "/Getting_Started.pdf",
            "is_dir" => false,
            "icon" => "page_white_acrobat",
            "root" => "dropbox",
            "mime_type" => "application/pdf",
            "revision" => 220823,
        ];
        return $result;
    }

    public function error()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");

        $this->code = 401;
        
        return ['error' => 'Not found!'];
    }
}
