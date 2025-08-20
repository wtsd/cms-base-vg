<?php
namespace wtsd\content;

use wtsd\common;
use wtsd\common\Database;
use wtsd\common\ProtoClass;
use wtsd\content\Gallery;
/**
* Defines the gallery entity for images` folder representation.
* Includes photos and metainformation.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.2
*/
class Image extends ProtoClass
{
    const SQL_LOAD_BY_GALID = 'SELECT * FROM `tblImage` WHERE `gal_id` = :gal_id',
            SQL_INSERT = 'INSERT INTO `tblImage` (`fname`, `cdate`, `gal_id`) VALUES (:fname, Now(), :gal_id)',
            SQL_DELETE = 'DELETE FROM `%s` WHERE `id` = :id';

    public $id, $name, $lead;
    public $_table = 'tblImage';
    protected $c_type = 'image';

    protected $_imagesDir = '/img/gallery/';

    protected $_cti = array(
        'name' => 'Изображение',
    );

    function __construct($id = '')
    {
        $this->addField('id', 'none', false, false);
        $this->addField('gal_id', 'cmb', true, true);
        $this->addField('name', 'text', true, true);
        $this->addField('descr', 'textarea');
        $this->addField('tags');
        $this->addField('cdate', 'time', false);
        
        parent::__construct($id);
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'gal_id' => '0',
            'name' => '',
            'descr' => '',
            'tags' => '',
            'fname' => '',
            'cdate' => date('Y-m-d H:i:s'),

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    function idToName($id)
    {
        if (!is_numeric($id)) {
            return;
        }

        $obj = $this->_getById($id);
    
        if ($obj['name']) {
            return htmlspecialchars($obj['name'], ENT_QUOTES, 'utf-8');
        } else {
            return '';
        }
    }

    public static function getPhotosByGallery($id)
    {
        if (!is_numeric($id)) {
            return;
        }

        $placeholders = array(':gal_id' => $id);
        $rows = Database::selectQuery(self::SQL_LOAD_BY_GALID, $placeholders);

        return $rows;
    }

    protected $foreignKey = 'gal_id';
    public function uploadAjax($arr)
    {
        $ext = 'jpg';
        $filenames = [];

        // Generating dir-name
        if (intval($arr['id']) > 0) {
            $id = trim(intval($arr['id']));
        } else {
            $id = 'tmp';
        }
        
        $dir = ROOT . $this->_imagesDir . $id . '/';

        // Creating dir if not exists
        $this->_createImageDirectories($dir);

        // Uploading files
        $images = [];
        for ($i = 0; $i < count($_FILES['image']['size']); $i++) {
            $new_name = $this->_generateFilename($dir . 'full/', $ext);

            if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $dir . 'full/' . $new_name)) {
                chmod($dir . 'full/' . $new_name, 0666);
                $images[] = $new_name;

                $placeholders = array(':fname' => $new_name, ':' . $this->foreignKey => $arr['id'],);
                $newId = Database::insertQuery(self::SQL_INSERT_IMAGES, $placeholders);
                $filenames[] = array('id' => $newId, 'name' => $new_name);

            } else {
                //echo ini_get('upload_tmp_dir');
                die('Ошибка #3417 загрузки файла.');
                throw new \Exception('Ошибка загрузки файла!');
            }
        }

        foreach ($images as $image) {
            $this->imageResize($dir . 'full/' . $image, 300, 200, $dir . 'thumb/' . $image);
        }

        if (count($filenames) > 0) {
            $status = 'ok';
            $message = 'Файлы благополучно загружены';
        } else {
            $status = 'error';
            $message = 'Произошла ошибка при загрузке файлов';
        }
        return json_encode(array('status' => $status, 'path' => $this->_imagesDir . $id . '/full/', 'files' => $filenames, 'msg' => $message));
    }

    public function rotateAjax($args)
    {
        $id = $args['values']['id'];
        $galId = $args['values']['galleryId'];
        $dir = ROOT . $this->_imagesDir . $galId . '/';

        // @todo: Get filename
        $sql = "SELECT * FROM `tblImage` WHERE `id` = :id LIMIT 1";
        $placeholders = array(
            ':id' => intval($id)
            );
        $row = Database::selectQuery($sql, $placeholders, true);
        $fname = $row['fname'];

        $this->imageRotate($dir . 'full/' . $fname);

        $this->imageResize($dir . 'full/' . $fname, 300, 200, $dir . 'thumb/' . $fname);

        return json_encode(array('status' => 'ok'));
    }

    protected function _preSave()
    {
        $dir = ROOT . $this->_imagesDir;
        $ext = 'jpg';
        // If gal_id changed, move file and update fname
        if (isset($this->_saveArr['id']) && intval($this->_saveArr['id']) > 0) {
            $row = $this->getById($this->_saveArr['id']);
            if ($row['gal_id'] != $this->_saveArr['gal_id']) {
                $newDir = $dir . $this->_saveArr['gal_id'] . '/';
                $newName = $this->_generateFilename($newDir . 'full/', $ext);

                $oldDir = $dir . $row['gal_id'] . '/';
                $oldName = $row['fname'];

                if (!file_exists($newDir)) {
                    mkdir($newDir . 'full/', 0777, true);
                    mkdir($newDir . 'thumb/', 0777, true);
                }

                rename($oldDir . 'full/' . $oldName, $newDir . 'full/' . $newName);
                rename($oldDir . 'thumb/' . $oldName, $newDir . 'thumb/' . $newName);

            }
        }
    }

}
