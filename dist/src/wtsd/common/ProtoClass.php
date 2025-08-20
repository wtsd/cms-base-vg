<?php
namespace wtsd\common;

use wtsd\common;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class ProtoClass
{
    const SQL_UPD_IMAGES = "UPDATE `%s` SET `%s` = :id WHERE `id` IN (%s)",
            SQL_UPD_ATTACHMENTS = "UPDATE `%s` SET `%s` = :id WHERE `id` IN (%s)",
            SQL_GET_IMAGES_BY_IDS = "SELECT * FROM `%s` WHERE `id` IN (%s)",
            SQL_GET_ALL = "SELECT * FROM `%s` ORDER BY `id` DESC",
            SQL_INSERT_IMAGES = 'INSERT INTO `%s` (`fname`, `cdate`, `%s`) VALUES (:fname, Now(), :%s)',
            SQL_INSERT_ATTACH = 'INSERT INTO `%s` (`fname`, `cdate`, `%s`, `name`) VALUES (:fname, Now(), :%s, :name)',
            SQL_GET_IMAGES = 'SELECT * FROM `%s` WHERE `%s` = :%s',
            SQL_GET_ATTACHMENTS = 'SELECT * FROM `%s` WHERE `%s` = :%s';

    protected $c_type = '';
    protected $labels = [];

    public $_table = '';

    protected $imagesTable = '';
    protected $imagesFk = '';
    protected $imagesDir = '/img/';
    protected $uploadDir = '/uploads/';
    
    protected $attachmentTable = '';
    protected $attachmentFk = '';

    protected $_fields = [];

    protected $_delayed = false;

    /* Listing */
    protected $_perPage = 20;

    protected $id = 0;

    protected $is_routed = false;
    
    protected $_saveArr = [];

    public function __construct($id = '')
    {
        if ($id) {
            $this->id = $id;
        }

        $this->labels = Register::get('lang', 'admin');

        if (is_numeric($this->id)) {
            $arr = $this->getById($this->id);
            if (is_array($arr)) {
                $this->setProperties($arr);
            }
        }

    }

    public function getId()
    {
        return $this->id;
    }

    public function setProperties($arr)
    {
        if (is_array($arr)) {
            foreach ($arr as $field => $value) {
                $this->$field = $value;
            }
        }
    }

    /**
    * Adding a new field and assigning options.
    * 
    * @param string $name       Name of a field in a database.
    * @param enum $type         Default: 'text'
    * @param bool $required     Default: false
    * @param bool $editable     Default: true
    */
    public function addField($name, $type = 'text', $required = false, $editable = true)
    {
        $fieldInfo = [];

        $fieldInfo['itype'] = $type;
        $fieldInfo['required'] = $required;
        $fieldInfo['editable'] = $editable;

        $this->_fields[$name] = $fieldInfo;
    }

    /**
     *
     *
     */
    public function getById($id)
    {
        if ($id > 0) {
            $sql = sprintf("SELECT * FROM `%s` WHERE `id` = :id LIMIT 1", $this->_table);
            $placeholders = array(':id' => $id);
            $row = Database::selectQuery($sql, $placeholders, true);
            return $row;
        } else {
            return $this->getEmpty();
        }

    }

    public function getEmpty()
    {
        return array(
            'id' => 0,
            'cdate' => date('Y-m-d H:i:s'),
            'status' => '1',
            );
    }

    public function getAll()
    {
        $sql = sprintf(self::SQL_GET_ALL, $this->_table);
        $rows = Database::selectQuery($sql);
        return $rows;
    }

    protected function _getCount($filter = null)
    {
        $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s`", $this->_table);
        $rows = Database::selectQuery($sql_all);

        return $rows[0]['cnt'];
    }

    protected function _getRecords($off, $perp, $filter = null)
    {
     
        $sql = sprintf("SELECT * FROM `%s` ORDER BY `id` DESC LIMIT :off, :perpage", $this->_table);
        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':perpage' => array('type' => 'int', 'value' => $perp),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows;
    }

    public function lst($page = 1, $filter = null)
    {
        $cnt = $this->_getCount($filter);
        $pages = 0;

        $records = [];
        if ($cnt > 0) {
            $pages = floor($cnt / $this->_perPage) + 1;

            $off = intval($this->_perPage * ($page - 1));
            $perp = intval($this->_perPage);

            $records = $this->_getRecords($off, $perp, null, $filter);

        }
        $arr = array(
            'records' => $records,
            'ctype' => $this->c_type,
            'curPage' => $page,
            'pages' => intval($pages),
            'preUrl' => sprintf('/adm/%s/browse/', $this->c_type)
        );
        return $arr;
    }

    protected function _preSave()
    {
    }

    protected function _postSave($id, $arr = '', $isInserted = true)
    {
    }

    protected function prepare($arr)
    {
        $preparedFields = array(
            'arr_fields' => [],
            'placeholders' => []
            );
        foreach ($this->_fields as $field => $props) {
            if ($props['editable'] == true) {
                $preparedFields['arr_fields'][] = sprintf("`%s` = :%s", $field, $field);

                if (($props['required']) && ($arr[$field] == '') && $props['itype'] != 'file') {
                    return array(false, $field);
                }
                switch ($props['itype']) {
                    case 'cmb':
                        $value = intval($arr[$field]);
                        break;
                    case 'checkbox':
                        if (isset($arr[$field])) {
                            $value = '1';
                        } else {
                            $value = '0';
                        }
                        break;
                    case 'time':
                        if (isset($arr[$field])) {
                            $value = $arr[$field];
                        } else {
                            $value = date("Y-m-d H:i:s");
                        }
                        break;
                    default:
                        if (!isset($arr[$field]) && !$props['required']) {
                            $value = null;
                        } else {
                            $value = $arr[$field];
                        }
                        break;
                }
                $preparedFields['placeholders'][':' . $field] = $value;

            }
        }
        return array(true, $preparedFields);
    }

    public function save($arr)
    {

        $this->_saveArr = $arr;
        foreach ($this->_saveArr as $field => $value) {
            $this->$field = $value;
        }

        try {
            $this->preparedFields = $this->prepare($this->_saveArr);
            $result = $this->_preSave();
            if ($result['status'] == 'error') {
                return $result;
            }

            if (!$this->preparedFields[0]) {
                return array('status' => 'error', 'msg' => 'Required fields! '.$this->preparedFields[1], 'id' => 0, 'errors' => array('Required fields must be filled!'));
            }
            $this->preparedFields = $this->preparedFields[1];
            if (intval($this->_saveArr['id']) > 0) {
                $sql = "UPDATE `" . $this->_table . "` SET " . implode(', ', $this->preparedFields['arr_fields']) . " WHERE `id` = :id";
                $this->preparedFields['placeholders'][':id'] = $this->_saveArr['id'];
            } else {
                $sql = sprintf("INSERT INTO `%s` SET %s", $this->_table, implode(', ', $this->preparedFields['arr_fields']));
            }

            try {
                $newId = Database::insertQuery($sql, $this->preparedFields['placeholders']);
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    return array('status' => 'error', 'msg' => Register::get('lang', 'recordexists'), 'id' => 0, 'errors' => []);
                }
            }

            if (intval($this->_saveArr['id']) == 0) {
                $this->_saveArr['id'] = $newId;
            }

            $this->_postSave($this->_saveArr['id'], $arr);

            $labels = Register::get('lang', 'admin');
            return array('status' => 'ok', 'msg' => $labels['msgs']['all_saved'], 'id' => $this->_saveArr['id'], 'errors' => []);
        } catch (Exception $e) {
            return array('status' => 'error', 'msg' => $e->getMessage(), 'id' => 0, 'errors' => []);
        }
    }

    public function delete($id = '', $is_ajax = false)
    {
        if (!is_numeric($id)) {
            return;
        }

        $sql = sprintf("DELETE FROM `%s` WHERE `id` = :id", $this->_table);
        $placeholders = array(':id' => $id);
        Database::deleteQuery($sql, $placeholders);

        $labels = Register::get('lang', 'admin');

        if ($is_ajax == true) {
            return array('ok' => 1, 'msg' => $labels['msgs']['all_deleted'], 'id' => $id);
        } else {
            $arr = array(
                'title' => $this->labels['title'],
                'contents' => $this->labels['redir'],
                'url' => sprintf('/adm/%s/browse/', $this->c_type)
            );

            $view = new Template('admin');
            $view->assignAll($arr);

            $result = $view->render('redir.tpl');

            echo $result;
            exit;
        }
        
    }


    /* Images management */

    public function generateFilename($dir, $ext = 'jpg')
    {
        do {
            $new_name = intval(uniqid(rand(), true)) . '.' . $ext;
        } while (file_exists($dir . $new_name));

        return $new_name;
    }

    public function getForeignKey()
    {
        return $this->foreignKey;
    }
    public function getUploadDir()
    {
        return $this->uploadDir;
    }
    public function getAttachmentTable()
    {
        return $this->attachmentTable;
    }
    public function getAttachmentFk()
    {
        return $this->attachmentFk;
    }

    public function getImagesDir()
    {
        return $this->imagesDir;
    }

    protected function _generateFilename($dir, $ext = 'jpg')
    {
        return $this->generateFilename($dir, $ext);
    }

    static public function imageResize($fname, $width, $height, $tfile)
    {
        if (!file_exists($fname)) {
            throw new \Exception('File not found!');
        }
        /* Get original file size */
        list($w, $h) = getimagesize($fname);

        /* Calculate new image size */
        if ($h > $w) {
            $tmp = $width;
            $width = $height;
            $height = $tmp;
        }
        $ratio = max($width/$w, $height/$h);
        $h = ceil($height / $ratio);
        $x = ($w - $width / $ratio) / 2;
        $w = ceil($width / $ratio);
        
        /* set new file name */
        $path = $tfile;

        //echo "path: $path";
        /* Save image */
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fname);
        if ($mime == 'image/jpeg' || $mime == 'image/jpg') {
            /* Get binary data from image */
            $imgString = file_get_contents($fname);
            /* create image from string */
            $image = imagecreatefromstring($imgString);
            $tmp = imagecreatetruecolor($width, $height);
            imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
            imagejpeg($tmp, $path, 100);
        } elseif ($mime == 'image/png') {
            $image = imagecreatefrompng($fname);
            $tmp = imagecreatetruecolor($width, $height);
            imagealphablending($tmp, false);
            imagesavealpha($tmp, true);
            imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);
            imagepng($tmp, $path, 0);
        } elseif ($mime == 'image/gif') {
            $image = imagecreatefromgif($fname);

            $tmp = imagecreatetruecolor($width, $height);
            $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $transparent);
            imagealphablending($tmp, true); 

            imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $w, $h);
            imagegif($tmp, $path);
        } else {
            return false;
        }

        return true;
        imagedestroy($image);
        imagedestroy($tmp);
    }

    public function addWatermark($original, $watermark)
    {
        // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefrompng(ROOT . $watermark);

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 10;
        $marge_bottom = 10;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);


        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $original);
        if ($mime == 'image/jpeg' || $mime == 'image/jpg') {
            $im = imagecreatefromjpeg($original);
            imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

            imagejpeg($im, $original, 100);
        } elseif ($mime == 'image/png') {
            $im = imagecreatefrompng($original);
            imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    
            imagepng($im, $original);
        } elseif ($mime == 'image/gif') {
            $im = imagecreatefromgif($original);
            imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));

            imagegif($im, $original);
        }

        imagedestroy($im);
    }

    public function upload(array $fileArr, $id, $dir, $type = 'image', $i = 0)
    {
        
        $arrname = explode('.', $fileArr['name'][$i]);
        $ext = array_pop($arrname);

        $new_name = $this->generateFilename($dir, $ext);
        $orig_name = $fileArr['name'][$i];

        if (isset($fileArr['size'][$i])) {
            $src = $fileArr['tmp_name'][$i];
        } else {
            $src = $fileArr['tmp_name'];
        }
        if (!file_exists($dir)) {
            mkdir($dir, 0777);
        }

        if (move_uploaded_file($src, $dir . $new_name)) {
            chmod($dir . $new_name, 0666);
            $files[] = $new_name;

            if ($type == 'image') {
                $placeholders = array(':fname' => $new_name, ':' . $this->foreignKey => $id,);
                $newId = Database::insertQuery(sprintf(self::SQL_INSERT_IMAGES, $this->imagesTable, $this->imagesFk, $this->imagesFk), $placeholders);
            }
            if ($type == 'attachment') {

                $placeholders = array(
                    ':fname' => $new_name,
                    ':'.$this->attachmentFk => $id,
                    ':name' => $orig_name,
                    );
                $newId = \wtsd\common\Database::insertQuery(sprintf(self::SQL_INSERT_ATTACH, $this->attachmentTable, $this->attachmentFk, $this->attachmentFk), $placeholders);
            }

            $tmpArr = explode('.', $new_name);
            $filetype = array_pop($tmpArr);

            $filename = array('id' => $newId, 'name' => $new_name, 'filetype' => $filetype, 'orig_name' => $orig_name);
        } else {
            throw new \Exception(Register::get('lang', 'uploaderror'));
        }
        return $filename;
    }

    /**
     * For files uploaded as an addition to the entity.
     */
    public function moveFile($from, $to)
    {
        $errors = [];
        if (file_exists($from)) {
            if (copy($from, $to)) {
                //echo 'File copied!' . PHP_EOL;
            } else {
                $error[] = 'Error while copying a file!';
            }

            /*if (unlink($from)) {
                //echo 'Succesfully deleted!' . PHP_EOL;
            }*/

        } elseif (file_exists($to)) {
            $error[] = 'File already exists';
        } else {
            $error[] = 'Unknown error!';
        }
    }

    public function imageRotate($fname, $angle = 270)
    {
        $image = imagecreatefromjpeg($fname);
        $rotate = imagerotate($image, $angle, 0);
        imagejpeg($rotate, $fname, 100);
    }

    public function createImageDirectories($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($dir . 'full/')) {
            mkdir($dir . 'full/', 0777, true);
        }
        if (!file_exists($dir . 'thumb/')) {
            mkdir($dir . 'thumb/', 0777, true);
        }
    }

    protected function uploadImages($id)
    {
        if (isset($_POST['images'])) {
            $images = array_map('intval', $_POST['images']);
            $sql = sprintf(self::SQL_UPD_IMAGES, $this->imagesTable, $this->imagesFk, implode(', ', $images));

            $placeholders = array(':id' => $id);
            $newId = Database::insertQuery($sql, $placeholders);

            $f_dir = ROOT . $this->imagesDir . 'tmp/';
            $t_dir = ROOT . $this->imagesDir . intval($id) . '/';
            if (!file_exists($t_dir)) {
                mkdir($t_dir . 'full', 0777, true);
                mkdir($t_dir . 'thumb', 0777, true);
            }

            $sql = sprintf(self::SQL_GET_IMAGES_BY_IDS, $this->imagesTable, implode(', ', $images));
            $imagesInfo = Database::selectQuery($sql);
            foreach ($imagesInfo as $image) {
                if (!is_writable($t_dir)) {
                    throw new Exception('Not writable directory (' . $t_dir . ')');
                    //die('Not writable directory (' . $t_dir . ')');
                }

                $this->moveFile($f_dir . 'full/' . $image['fname'], $t_dir . 'full/' . $image['fname']);
                $this->moveFile($f_dir . 'thumb/' . $image['fname'], $t_dir . 'thumb/' . $image['fname']);
            }

            // @todo: Replace tmp image sources to new dirs
        }
    }

    protected function uploadAttachments($id)
    {
        if (isset($_POST['attachments'])) {
            $attachments = array_map('intval', $_POST['attachments']);
            $sql = sprintf(self::SQL_UPD_ATTACHMENTS, $this->attachmentTable, $this->attachmentFk, implode(', ', $attachments));

            $placeholders = array(':id' => $id);
            $newId = Database::insertQuery($sql, $placeholders);

            $f_dir = ROOT . $this->uploadDir . 'tmp/';
            $t_dir = ROOT . $this->uploadDir . intval($id) . '/';
            if (!file_exists($t_dir)) {
                mkdir($t_dir, 0777, true);
            }

            $sql = sprintf(self::SQL_GET_IMAGES_BY_IDS, $this->attachmentTable, implode(', ', $attachments));
            $attachmentsInfo = Database::selectQuery($sql);
            foreach ($attachmentsInfo as $attachment) {
                if (!is_writable($t_dir)) {
                    throw new Exception('Not writable directory (' . $t_dir . ')');
                    //die('Not writable directory (' . $t_dir . ')');
                }

                $this->moveFile($f_dir . $attachment['fname'], $t_dir . $attachment['fname']);
            }

        }
    }

    public function getPhotos($id)
    {
        if (!$id) {
            return null;
        }

        $placeholders = array(':'.$this->imagesFk => $id);
        $rows = Database::selectQuery(sprintf(self::SQL_GET_IMAGES, $this->imagesTable, $this->imagesFk, $this->imagesFk), $placeholders);

        return $rows;
    }

    public function getAttachments($id)
    {
        if (!is_numeric($id)) {
            return null;
        }

        $placeholders = array(':'.$this->attachmentFk => $id);
        $rows = Database::selectQuery(sprintf(static::SQL_GET_ATTACHMENTS, $this->attachmentTable, $this->imagesFk, $this->imagesFk), $placeholders);
        for ($i = 0; $i < count($rows); $i++) {
            $tmpArr = explode('.', $rows[$i]['fname']);
            $rows[$i]['filetype'] = array_pop($tmpArr);
        }

        return $rows;
    }

    public function DeleteImageAjax($values)
    {
        // @todo: Delete file and record in db
        $placeholders = array(
            ':id' => $values['values']['id']
            );
        $sqlSelect = sprintf("SELECT * FROM `%s` WHERE `id` = :id LIMIT 1", $this->imagesTable);
        $row = Database::selectQuery($sqlSelect, $placeholders, true);

        $sqlDelete = sprintf("DELETE FROM `%s` WHERE `id` = :id LIMIT 1", $this->imagesTable);
        Database::deleteQuery($sqlDelete, $placeholders);

        if ($row[$this->imagesFk] > 0) {
            $dir = ROOT . $this->imagesDir . $row[$this->imagesFk] . '/';
        } else {
            $dir = ROOT . $this->imagesDir . 'tmp' . '/';
        }
        $fname_full = $dir . 'full/' . $row['fname'];
        $fname_thumb = $dir . 'thumb/' . $row['fname'];
        unlink($fname_full);
        unlink($fname_thumb);
        return json_encode(array('status' => 'error', 'row' => $row));
    }
        
    public function SaveAjax($values)
    {
        $arr = $this->save($values);
        echo json_encode($arr);
        exit;
    }

    public function DeleteAjax($values)
    {
        $arr = $this->delete($values['values']['id'], true);
        echo json_encode($arr);
        exit;
    }

    public function isRouted()
    {
        return ($this->is_routed);
    }
}