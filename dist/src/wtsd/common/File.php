<?php
namespace wtsd\common;

use wtsd\common\Register;
/**
* File manipulation class.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1.1
*/
class File
{
    protected $_name;
    protected $_mime;
    protected $_path;
    
    static public function upload($dir, $fieldname, $fext = null, $overwrite = true, $mime = null)
    {
        if (isset($_FILES[$fieldname]['size'])) {
            $allFiles = [];
            $absoluteDir = ROOT . $dir;
            for ($i = 0; $i < count($_FILES[$fieldname]['size']); $i++) {

                $cur_file = $_FILES[$fieldname];
                $fname = $i . '.' . $fext;
                $fpath = $absoluteDir . $fname;
                if (!file_exists($absoluteDir)) {
                    mkdir($absoluteDir, 0777, true);
                } elseif (file_exists($fpath) && file_exists($cur_file['tmp_name'][$i])) {
                    unlink($fpath);
                }

                move_uploaded_file($cur_file['tmp_name'][$i], $fpath);
                $allFiles[] = $dir . $fname;

            }

            return implode(',', $allFiles);
        } else {
            return '';
        }
    }
    
    public static function fileInfo($fpath)
    {
        $result = [];
        $result['fpath'] = $fpath;
        if (file_exists(ROOT . $fpath)) {
            $finfo = new \finfo(FILEINFO_MIME);
            $type = $finfo->file(ROOT . $fpath);
            $result['mime'] = substr($type, 0, strpos($type, ';'));
            $result['size'] = round((filesize(ROOT . $fpath) / 1024), 1) . ' Kbytes';
        }
        return $result;
    }
    
    public static function getFilelist($dir, $id, $ext)
    {
        $links = [];
        $path = $dir . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;
        for ($i = 0; file_exists(ROOT . $path . $i . '.' . $ext); $i++) {
            $links[] = self::fileInfo($path . $i . '.' . $ext);
        }
        return $links;
    }

    public function ajaxFormAjax($name, $multiple = true)
    {
    
    }
    
    public static function DeleteAjax($values)
    {
        $_LABELS = Register::get('lang');
        if (str_replace('..', '', $values['values']['fpath']) != $values['values']['fpath']) {
            exit;
        }

        if (unlink($values['values']['fpath'])) {
            $result = json_encode(array('ok' => 1, 'msg' => 'File saved', 'id' => $fname));
            die($result);
        }
    }
}
