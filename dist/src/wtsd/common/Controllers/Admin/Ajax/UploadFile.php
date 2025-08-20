<?php
namespace wtsd\common\Controllers\Admin\Ajax;

use wtsd\common\Controllers\Admin\Ajax;
use wtsd\common\Request;
use wtsd\common\Template;
use wtsd\common\Market\PSpec;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class UploadFile extends Ajax
{

    public function run()
    {

        $result = array();
        $result['status'] = 'error';

        $type = Request::parseUrl(3);
        $id = Request::parseUrl(4);
        $ftype = Request::parseUrl(5);

        $id = (intval($id) > 0) ? $id : 0;
        $ftype = ($ftype == '') ? 'attachment' : $ftype;

        $obj = $this->objectFactory($type, $id);

        $filenames = array();

        if ($ftype == 'attachment') {
            $dir = ROOT . $obj->getUploadDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
        }
        if ($ftype == 'image') {
            $dir = ROOT . $obj->getImagesDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
            $obj->createImageDirectories($dir);
        }

        if (isset($_FILES['upload'])) {
            $ftype = 'upload';
            $dir = ROOT . $obj->getImagesDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
            $obj->createImageDirectories($dir);
        }

        $subdir = 'full/';
        if ($ftype == 'slider') {
            $subdir = '';
        }
        try {
            if (isset($_FILES[$ftype])) {
                for ($i = 0; $i < count($_FILES[$ftype]['tmp_name']); $i++) {
                    $fileArr = $_FILES[$ftype];
                    if ($ftype == 'image' || $ftype == 'upload') {
                        $filename = $obj->upload($fileArr, $id, $dir . 'full/', 'image', $i);
                        $obj->moveFile($dir . 'full/'.$filename['name'], $dir . 'thumb/'.$filename['name']);
                        $filenames[] = $filename;
                        /*foreach ($filenames as $image) {
                            $obj->imageResize($dir . 'full/' . $image['name'], 1200, 800, $dir . 'thumb/' . $image['name']);
                        }*/
                        $result['path'] = $obj->getImagesDir() . ((intval($id) > 0) ? $id : 'tmp') . '/full/';
                    }
                    if ($ftype == 'attachment') {
                        $filenames[] = $obj->upload($fileArr, $id, $dir . '/', $ftype);
                        $result['path'] = $obj->getUploadDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
                    }
                }

                $result['status'] = 'ok';
                $result['files'] = $filenames;
                $result['msg'] = 'Файлы благополучно загружены';
                $result['url'] = $result['path'] . $filenames[0]['name'];

                if ($ftype == 'upload' && isset($_GET['CKEditorFuncNum'])) {
                    $funcNum = isset($_GET['CKEditorFuncNum']) ? $_GET['CKEditorFuncNum'] : '';
                    // Optional: instance name (might be used to load a specific configuration file or anything else).
                    $CKEditor = isset($_GET['CKEditor']) ? $_GET['CKEditor'] : '';
                    // Optional: might be used to provide localized messages.
                    $langCode = isset($_GET['langCode']) ? $_GET['langCode'] : '';
                    $url = $result['url'];
                    die("<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '');</script>");
                }

                if ($ftype == 'upload') {
                    die($result['url']);
                }
            } else {
                $result['msg'] = 'Файл не отправлен.';
            }
        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['msg'] = $e->getMessage();
        }

        return $result;
    }

    protected function objectFactory($type, $id)
    {
        if ($type == 'offer') {
            return new \wtsd\market\Offer($id);
        }

        if ($type == 'article') {
            return new \wtsd\content\Article($id);
        }

        if ($type == 'gallery') {
            return new \wtsd\content\Gallery($id);
        }

        if ($type == 'pcategory') {
            return new \wtsd\market\PCategory($id);
        }

        throw new \Exception('Undefined object type.');
    }
    
}
