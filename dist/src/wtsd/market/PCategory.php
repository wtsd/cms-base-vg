<?php
namespace wtsd\market;

use wtsd\common;
use PDO;
use wtsd\common\ProtoClass;
use wtsd\common\Database;
use wtsd\market\Offer;
use wtsd\common\Content\Image;
/**
* Defines the product category entity, which is the representation 
* of the products those are provided by the shop.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class PCategory extends ProtoClass
{
    const SQL_LOAD_SUBPCATS = 'SELECT * FROM `tblPCategory` WHERE `pcat_id` = :id',
            SQL_LOAD_OFFERS = 'SELECT * FROM `tblOffer` `o` WHERE `pcat_id` = :id AND `o`.`status` = 1 ORDER BY `o`.`ord` ASC, `o`.`cdate` DESC',
            SQL_UPD_PHOTO = 'UPDATE `tblPCategory` SET `photo` = :photo WHERE `id` = :id',
            SQL_BY_ID = 'SELECT * FROM `tblPCategory` WHERE `id` = :id LIMIT 1',
            SQL_GET_BY_PARENT = 'SELECT * FROM `tblPCategory` WHERE `pcat_id` = :id',
            SQL_GET_BY_REWRITE = 'SELECT * FROM `tblPCategory` WHERE `rewrite` = :rewrite LIMIT 1',
            SQL_GET_ACTIVE = 'SELECT * FROM `tblPCategory` WHERE `status` = 1',
            SQL_GET_ALL = 'SELECT `p`.*, `pr`.`name` AS `parent_name`, count(`o`.`id`) AS `offers_cnt` FROM `tblPCategory` `p` LEFT JOIN `tblPCategory` `pr` ON `p`.`pcat_id` = `pr`.`id` LEFT JOIN `tblOfferPcat` `pc` ON `p`.`id` = `pc`.`pcat_id` LEFT JOIN `tblOffer` `o` ON `o`.`id` = `pc`.`offer_id` GROUP BY `p`.`id` ORDER BY `p`.`id` DESC',// LIMIT :offset, :limit',
            SQL_GET_ALL_CNT = 'SELECT count(*) AS `cnt` FROM `tblPCategory` `p`',
            SQL_GET_BY_ID = 'SELECT `p`.*, `pr`.`name` AS `parent_name`, count(`o`.`id`) AS `offers_cnt` FROM `tblPCategory` `p` LEFT JOIN `tblPCategory` `pr` ON `p`.`pcat_id` = `pr`.`id` LEFT JOIN `tblOffer` `o` ON `o`.`pcat_id` = `p`.`id` WHERE `p`.`id` = :id GROUP BY `p`.`id`';

    public $_table = 'tblPCategory';

    protected $imagesDir = '/img/pcategory/';

    protected $imagesTable = 'tblPCategoryImages';
    protected $imagesFk = 'pcat_id';
    protected $foreignKey = 'pcat_id';


    protected $c_type = 'pcategory';


    protected $_fields = [];

    public function __construct($id = 0)
    {

        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('rewrite', 'text', true, true);
        $this->addField('descr', 'textarea');
        $this->addField('cdate', 'time');
        $this->addField('mdate', 'time', false, false);
        $this->addField('status', 'cmb', true);
        $this->addField('pcat_id', 'cmb');
        $this->addField('ord');
        $this->addField('post_desc', 'textarea');
        $this->addField('photo');

        $this->addField('h1');
        $this->addField('h2');
        $this->addField('meta_keywords');
        $this->addField('meta_description');
        $this->addField('title');

        //parent::__construct($id);

        if (is_numeric($id) && $id > 0) {
            $row = $this->getById($id);
            $this->setProperties($row);
            
            $placeholders = array(':id' => $row['id']);

            //$offers = Database::selectQuery(self::SQL_LOAD_OFFERS, $placeholders);
            /*foreach ($offers as $offer) {
                $this->offers[] = new Offer($offer['id']);
            }*/
        } elseif (mb_strlen($id) > 0) {
            $row = $this->fromRewrite($id);
            $this->setProperties($row);

            $placeholders = array(':id' => $row['id']);

            //$this->offers = Offer::getTop($row['rewrite']);

        } else {
            parent::__construct();
            $categories = Database::selectQuery(self::SQL_GET_ACTIVE);
            foreach ($categories as $category) {
                $this->subcats[] = new self($category['id']);
            }
            
            //$this->offers = Offer::getTop();

            $placeholders = array(':id' => 0);
        }

        $categories = Database::selectQuery(self::SQL_LOAD_SUBPCATS, $placeholders);
        foreach ($categories as $category) {
            $this->subcats[] = new self($category['id']);
        }

    }


    public function getAll($limit = 0, $page = 1)
    {
        $placeholders = [];
        /*
        if ($page == 0) {
            $page = 1;
        }
        if ($limit == 0) {
            $limit = 100;
        }

        $placeholders[':limit'] = array('type' => 'int', 'value' => intval($limit));
        $placeholders[':offset'] = array('type' => 'int', 'value' => ($page - 1) * $limit);
        */
        
        $sql = sprintf(self::SQL_GET_ALL, $this->_table);
        $rows = Database::selectQueryBind($sql, $placeholders);
        return $rows;
    }

    public function getAllCount()
    {
        return Database::selectQuery(self::SQL_GET_ALL_CNT, null, true)['cnt'];
    }

    public function load($id)
    {
        $row = Database::selectQuery(self::SQL_GET_BY_ID, array(':id' => $id), true);
        $row['images'] = $this->getPhotos($id);
        return $row;
    }


    public function delete($id = '', $is_ajax = false)
    {
        if (!is_numeric($id)) {
            return;
        }

        $sql = sprintf("DELETE FROM `%s` WHERE `id` = :id", $this->_table);
        $placeholders = array(':id' => $id);
        Database::deleteQuery($sql, $placeholders);

        return true;
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
            'rewrite' => '',
            'descr' => '',
            'cdate' => date('Y-m-d H:i:s'),
            'mdate' => date('Y-m-d H:i:s'),
            'status' => '1',
            'pcat_id' => '0',
            'ord' => '0',
            'post_desc' => '',
            'photo' => '',
            
            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    /**
     * For admin's panel tree combo input contents generation.
     * 
     */
    static public function getHierarchy($cat_id = 0, $level = 0)
    {

        $prefix = '';
        for ($i = 0; $i < $level; $i++) {
            $prefix .= '--';
        }

        $arr = [];
        if ($level == 0) {
            $arr[] = array('id' => 0, 'name' => '-- --');
        }

        $sql = "SELECT * FROM `tblPCategory` WHERE `pcat_id` = :pcat_id ORDER BY `ord`";
        $placeholders = array(':pcat_id' => $cat_id);
        $objs = Database::selectQuery($sql, $placeholders);
        foreach ($objs as $row) {
            $arr[] = array('id' => $row['id'], 'name' => $prefix . ' ' . $row['name']);
            $tmp_arr = self::getHierarchy($row['id'], ($level + 1));
            $arr = array_merge($arr, $tmp_arr);
        }
        return $arr;
    }

    static public function combo($id)
    {
        return array(
            'options' => self::getHierarchy(),
            'value' => $id
            );
    }
    
    static public function idToName($id)
    {
        if (!is_numeric($id)) {
            return;
        }
      
        $placeholders = array(':id' => $id);
        $obj = Database::selectQuery(self::SQL_BY_ID, $placeholders, true);

        if (isset($obj['name'])) {
            return htmlspecialchars($obj['name']);
        } else {
            return '/';
        }
    }

    public function getPCats($id = '')
    {
        if ($id === '') {
            $id = $this->id;
        }

        $placeholders = array(':id' => $id);
        $objs = Database::selectQuery(self::SQL_GET_BY_PARENT, $placeholders);

        for ($i = 0; $i < count($objs); $i++) {
            $objs[$i]['subcats'] = $this->getPCats($objs[$i]['id']);
            $objs[$i]['count'] = Offer::getCount($objs[$i]['rewrite']);
            $objs[$i]['photos'] = $this->getPhotos($objs[$i]['id']);
        }

        return $objs;
    }

    public static function fromRewrite($rewrite = 'all')
    {
        if ($rewrite ==! NULL) {
            $placeholders = array(':rewrite' => $rewrite);
            $result = Database::selectQuery(self::SQL_GET_BY_REWRITE, $placeholders, true);
            return $result;
        } else {
            return null;
        }
    }

    public function uploadAjax($arr)
    {
        $path = '/img/pcategory/';
        $ext = 'jpg';
        $filenames = [];

        // Generating dir-name
        if (intval($arr['id']) > 0) {
            $path .= trim(intval($arr['id'])) . '/';
        } else {
            $path .= 'tmp/';
        }
        
        $dir = ROOT . $path;

        // Creating dir if not exists
        if (!file_exists($dir)) {
            mkdir($dir . 'full/', 0777, true);
            mkdir($dir . 'thumb/', 0777, true);
        }

        // Uploading files
        $images = [];
        for ($i = 0; $i < count($_FILES['image']['size']); $i++) {
            do {
                $new_name = intval(uniqid(rand(), true)) . '.' . $ext;
            } while (file_exists($dir . 'full/' . $new_name));

            if (move_uploaded_file($_FILES['image']['tmp_name'][$i], $dir . 'full/' . $new_name)) {
                chmod($dir . 'full/' . $new_name, 0666);
                $images[] = $new_name;
                $filenames[] = array('id' => $new_name, 'name' => $new_name);
            } else {
                //echo ini_get('upload_tmp_dir');
                die('Ошибка загрузки файла.');
            }
        }

        // @todo: Resize and compress for previews
        foreach ($images as $image) {
            Image::imageResize($dir . 'full/' . $image, 300, 200, $dir . 'thumb/' . $image);
        }

        if (count($filenames) > 0) {
            $status = 'ok';
            $message = 'Файлы благополучно загружены';
        } else {
            $status = 'error';
            $message = 'Произошла ошибка при загрузке файлов';
        }
        return json_encode(array('status' => $status, 'path' => $path . 'full/', 'files' => $filenames, 'msg' => $message));
    }

    protected function _postSave($id, $arr = '', $isInserted = true)
    {
        if (isset($_POST['images'])) {
            $image = $_POST['images'][0];

            $placeholders = array(':id' => $id, ':photo' => $image);
            Database::updateQuery(self::SQL_UPD_PHOTO, $placeholders);

            $f_dir = ROOT . $this->imagesDir . 'tmp/';
            $t_dir = ROOT . $this->imagesDir . intval($id) . '/';
            if (!file_exists($t_dir)) {
                mkdir($t_dir . 'full', 0777, true);
                mkdir($t_dir . 'thumb', 0777, true);
            }

            if (!is_writable($t_dir)) {
                throw new Exception('Not writable directory (' . $t_dir . ')');
                //die('Not writable directory (' . $t_dir . ')');
            }

            $this->moveFile($f_dir . 'full/' . $image, $t_dir . 'full/' . $image);
            $this->moveFile($f_dir . 'thumb/' . $image, $t_dir . 'thumb/' . $image);
        }

    }

    public function getParentsArray()
    {
        $crumbs = $this->getParents();
        $crumbs[] = array('name' => 'Каталог товаров', 'rewrite' => '');
        return array_reverse($crumbs);
    }

    public function getParents($id = 0, $arr = null)
    {
        if ($id == 0) {
            $id = $this->id;
        }

        if ($arr === null) {
            $arr = [];
        }

        $sql = "SELECT * FROM `tblPCategory` WHERE `id` = :id";
        $placeholders = array(':id' => $id);
        $row = Database::selectQuery($sql, $placeholders, true);
        $arr[] = $row;
        if ($row['pcat_id'] > 0) {
            return $this->getParents($row['pcat_id'], $arr);
        } else {
            return $arr;
        }
        
    }

    protected function _getCount($filter = null)
    {
        $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s` WHERE `pcat_id` = 0", $this->_table);
        $row = Database::selectQuery($sql_all, null, true);

        return $row['cnt'];
    }


    protected function _getRecordsUp($parent, $offset = 0, $perp = 20)
    {
        $sql = "SELECT * FROM `tblPCategory` WHERE `pcat_id` = :parent";
        $placeholders = array(
            ':parent' => array('type' => 'int', 'value' => $parent),
            );

        if ($parent == 0) {
            $sql .= " LIMIT :offset, :perp";
            $placeholders[':offset'] = array('type' => 'int', 'value' => $offset);
            $placeholders[':perp'] = array('type' => 'int', 'value' => $perp);
        }

        $rows = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['subcats'] = $this->_getRecordsUp($rows[$i]['id']);
        }
        return $rows;
    }

    public function lst($page = 1, $filter = null)
    {
        $cnt = $this->_getCount();
        $pages = 0;

        $records = [];
        if ($cnt > 0) {
            $pages = floor($cnt / $this->_perPage) + 1;

            
            $off = intval($this->_perPage * ($page - 1));
            $perp = intval($this->_perPage);

            $rows = $this->_getRecordsUp(0, $off, $perp);

            foreach ($rows as $row) {
                foreach ($this->_fields as $field => $props) {
                    if (isset($props['lfunc'])) {
                        $row[$field] = call_user_func($props['lfunc'], $row[$field]);
                    }
                }
                $records[] = $row;
            }
        }

        $arr = array(
            'fields' => $this->_fields,
            'records' => $records,
            'ctype' => $this->c_type,
            'curPage' => $page,
            'pages' => intval($pages),
            'preUrl' => sprintf('/adm/%s/browse/', $this->c_type)
        );
        return $arr;
    }

    static public function doSearch($query, $off = 1)
    {
        $sqlQuery = '%' . $query . '%';
        $sql = "SELECT * FROM `tblPCategory` WHERE `name` LIKE :query1 OR `descr` LIKE :query2";
        $placeholders = array(
            ':query1' => array('type' => 'text', 'value' => $sqlQuery),
            ':query2' => array('type' => 'text', 'value' => $sqlQuery),
            //':limit' => array('type' => 'int', 'value' => $limit),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows;
    }
}

