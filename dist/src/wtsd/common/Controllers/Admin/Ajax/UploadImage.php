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
class UploadImage extends Ajax
{

    public function run()
    {

        $result = [];
        $result['status'] = 'error';

        $type = Request::parseUrl(3);
        $id = Request::parseUrl(4);
        $ftype = Request::parseUrl(5);

        $id = (intval($id) > 0) ? $id : 0;
        $ftype = ($ftype == '') ? 'image' : $ftype;

        $obj = $this->objectFactory($type, $id);

        $filenames = [];

        if ($ftype == 'attachment') {
            $dir = ROOT . $obj->getUploadDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
        }
        if ($ftype == 'image') {
            $dir = ROOT . $obj->getImagesDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
            $obj->createImageDirectories($dir);
        }

        try {
            if (isset($_FILES[$ftype])) {
                for ($i = 0; $i < count($_FILES[$ftype]['tmp_name']); $i++) {
                    $fileArr = $_FILES[$ftype];
                    if ($ftype == 'image') {
                        $filenames[] = $obj->upload($fileArr, $id, $dir . 'full/', $ftype, $i);

                        foreach ($filenames as $image) {
                            $obj->imageResize($dir . 'full/' . $image['name'], 300, 200, $dir . 'thumb/' . $image['name']);
                        }
                        $result['path'] = $obj->getImagesDir() . ((intval($id) > 0) ? $id : 'tmp') . '/full/';
                    }
                    if ($ftype == 'attachment') {
                        $filenames[] = $obj->upload($fileArr, $id, $dir . '/', $ftype, $i);
                        $result['path'] = $obj->getUploadDir() . ((intval($id) > 0) ? $id : 'tmp') . '/';
                    }

                    $result['status'] = 'ok';
                    $result['files'] = $filenames;
                    $result['msg'] = 'Файлы благополучно загружены';
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

    protected static $factory = [
        'offer' => '\wtsd\market\Offer',
        'article' => '\wtsd\content\Article',
        'gallery' => '\wtsd\content\Gallery',
        'pcategory' => '\wtsd\market\PCategory',

    ];

    protected function objectFactory($type, $id)
    {
        if (isset(self::$factory[$type])) {
            return new {self::$factory[$type]}($id);
        }
        
        throw new \Exception('Undefined object type.');
    }
    
}
