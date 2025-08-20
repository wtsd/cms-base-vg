<?php
namespace wtsd\content;

use wtsd\common;
use wtsd\common\Database;
use wtsd\common\ProtoClass;
use wtsd\common\Register;

/**
* Defines the gallery entity for images` folder representation.
* Includes photos and metainformation.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.2
*/
class Gallery extends ProtoClass
{
    const SQL_GET_BY_REWRITE = 'SELECT id FROM `tblGallery` WHERE `rewrite` = :rewrite LIMIT 1',
           SQL_GET_ALL_GALLERIES = 'SELECT `g`.*, `i`.`fname` FROM `tblGallery` `g` LEFT JOIN `tblImage` `i` ON `g`.`id` = `i`.`gal_id` WHERE `g`.`gal_id` = 0 GROUP BY `g`.`id` ORDER BY `i`.`cdate` DESC LIMIT :limit',
            SQL_LOAD_SUBGALLERY_ORD = 'SELECT * FROM `tblGallery` WHERE `gal_id` = :gallery_id ORDER BY `ord`',
            SQL_GET_ALL_IMAGES = 'SELECT * FROM `tblImage` WHERE `gal_id` = :id ORDER BY `cdate` DESC LIMIT :offset, :perpage',
            SQL_GET_ALL_IMAGES_COUNT = 'SELECT count(*) AS `cnt` FROM `tblImage` WHERE `gal_id` = :id',
            SQL_GET_ALL_SUBGALLERIES = 'SELECT `g`.*, `i`.`fname` FROM `tblGallery` `g` INNER JOIN `tblImage` `i` ON `i`.`gal_id` = `g`.`id` WHERE `g`.`gal_id` = :id GROUP BY `g`.`id` ORDER BY `cdate` DESC LIMIT :offset, :perpage',
            SQL_GET_BY_ID = 'SELECT * FROM `tblGallery` WHERE `id` = :id',
            SQL_GET_GAL_BY_PARENT = 'SELECT * FROM `tblGallery` WHERE `gal_id` = :gal_id ORDER BY `cdate` DESC',
            SQL_RANDOM_IMGS = "SELECT * FROM `tblImage` ORDER BY rand() LIMIT :limit";

    public $id, $name, $lead;
    public $_table = 'tblGallery';

    protected $imagesTable = 'tblImage';
    protected $imagesFk = 'gal_id';
    protected $imagesDir = '/img/gallery/';
    protected $foreignKey = 'gal_id';

    protected $c_type = 'gallery';
    
    function __construct($id = '')
    {
        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('rewrite', 'text', true, true);
        $this->addField('lead', 'textarea');
        $this->addField('tags');
        $this->addField('cdate', 'time', false);
        $this->addField('mdate', 'time', false, false);
        $this->addField('is_active', 'cmb', true);
        $this->addField('gal_id', 'cmb', true, true);

        $this->addField('h1');
        $this->addField('h2');
        $this->addField('meta_keywords');
        $this->addField('meta_description');
        $this->addField('title');
        
        parent::__construct($id);
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
            'rewrite' => '',
            'lead' => '',
            'tags' => '',
            'cdate' => date('Y-m-d H:i:s'),
            'mdate' => date('Y-m-d H:i:s'),
            'is_active' => '1',
            'gal_id' => '0',

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }
    
    protected function _getCount($filter = null)
    {
        if ($filter !== null) {
            $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s` WHERE `name` LIKE :filter", $this->_table);
            $placeholders = [':filter' => array('type' => 'string', 'value' => '%'.$filter.'%')];
            $rows = Database::selectQueryBind($sql_all, $placeholders);
        }
        if ($filter === null) {
            $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s`", $this->_table);
            $rows = Database::selectQuery($sql_all);
        }

        return $rows[0]['cnt'];
    }

    protected function _getRecords($off, $perp, $sort = null, $filter = null)
    {

        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':perpage' => array('type' => 'int', 'value' => $perp),
            );
        $sql_wh = '';
        if ($filter !== null) {
            $sql_wh = ' AND `g`.`name` LIKE :filter';
            $placeholders[':filter'] = array('type' => 'string', 'value' => '%'.$filter.'%');
        }     
        $sql = sprintf("SELECT `g`.*, `p`.`name` AS `gal_name`, `p`.`rewrite` AS `gal_rewrite` FROM `%s` `g` LEFT JOIN `tblGallery` `p` ON `p`.`gal_id` = `g`.`id` WHERE 1 %s ORDER BY `id` DESC LIMIT :off, :perpage", $this->_table, $sql_wh);
        $rows = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['images'] = $this->getPhotos($rows[$i]['id']);
        }
        return $rows;
    }

    static public function newFromRewrite($rewrite)
    {
        $placeholders = array(':rewrite' => $rewrite);
        $obj = Database::selectQuery(self::SQL_GET_BY_REWRITE, $placeholders, true);
        

        if (intval($obj['id']) > 0) {
            return new self($obj['id']);
        } else {
            $labels = Register::get('lang');
            throw new \Exception($labels['gallery']['notfound']);
        }
    }

    public function getBreadcrumb($id = null)
    {
        if ($id === null) {
            return [];
        }
        // @todo: Get gal_id of id till there is 0 in cat_id
        $placeholders = array(':id' => $id);
        $obj = Database::selectQuery(self::SQL_GET_BY_ID, $placeholders, true);
        $path = array();
        //$path[] = $obj;
        if (intval($obj['gal_id']) > 0) {
            $placeholders = array(':id' => $obj['gal_id']);
            $parent = Database::selectQuery(self::SQL_GET_BY_ID, $placeholders, true);
            if (intval($parent['gal_id']) > 0) {
                $placeholders = array(':id' => $parent['gal_id']);
                $path[] = Database::selectQuery(self::SQL_GET_BY_ID, $placeholders, true);
            }
            $path[] = $parent;
        }
        return $path;
    }

    static public function getPreview($gal_id)
    {
        $sql = "SELECT 
                `i`.`gal_id`,
                `i`.`fname`
                FROM `tblImage` `i`
                WHERE
                    `i`.`gal_id` = :gal_id
                GROUP BY `i`.`gal_id` LIMIT 1";
        //$sql = "SELECT `i`.*, `g`.`gal_id` AS `parent` FROM `tblImage` `i` INNER JOIN `tblGallery` `g` ON `i`.`gal_id` = `g`.`id` WHERE `i`.`gal_id` = :gal_id ORDER BY `i`.`id` DESC LIMIT 1";
        $placeholders = [':gal_id' => $gal_id];
        $subGal = Database::selectQuery($sql, $placeholders, true);
        if (!$subGal) {
            $sqlSub = "SELECT * FROM `tblGallery` WHERE `gal_id` = :gal_id LIMIT 1";
            $subGal2 = Database::selectQuery($sqlSub, $placeholders, true);
            if (!$subGal2) {
                return '';
            }
            return self::getPreview($subGal2['id']);
        }
        return $subGal['gal_id'].'/thumb/'.$subGal['fname'];
    }
    
    static public function allGalleries($cnt = 100)
    {
        $placeholders = array(':limit' => array('type' => 'int', 'value' => $cnt));
        $objs = Database::selectQueryBind(self::SQL_GET_ALL_GALLERIES, $placeholders);

        for ($i = 0; $i < count($objs); $i++) {
            if ($objs[$i]['fname'] == '') {
                $objs[$i]['fpath'] = self::getPreview($objs[$i]['id']);
            } else {
                $objs[$i]['fpath'] = $objs[$i]['id'] . '/thumb/' . $objs[$i]['fname'];
            }
        }
        $arr = array(
            'labels' => Register::get('lang'),
            'type' => 'galleries',
            'path' => '/img/gallery/',
            'galleries' => $objs,
            );

        return $arr;
    }

    // TODO: Add path of the gallery
    public function buildMain($page = 1)
    {
        $perpage = 30;
        $offset = ($perpage * ($page - 1));
        $arr = array(
            'labels' => Register::get('lang'),
            'path' => '/img/gallery/',
            );
        if ($this->id) {

            $placeholders = array(':id' => $this->id);
            $obj = Database::selectQuery(self::SQL_GET_BY_ID, $placeholders, true);

            if ($obj) {
                //$arr['galleries'] = self::galleryList($this->id);
                $arr['type'] = 'gallery';
                $arr['lead'] = $obj['lead'];
                $arr['title'] = $obj['name'];
                $arr['gallery'] = $obj;



                $placeholders = array(
                    ':offset' => array('type' => 'int', 'value' => $offset),
                    ':perpage' => array('type' => 'int', 'value' => $perpage),
                    ':id' => array('type' => 'int', 'value' => $this->id),
                    );
                $arr['images'] = $this->getPhotos($this->id);//Database::selectQueryBind(self::SQL_GET_ALL_IMAGES, $placeholders);
                $arr['galleries'] = Database::selectQueryBind(self::SQL_GET_ALL_SUBGALLERIES, $placeholders);

                $arr['breadcrumb'] = $this->getBreadcrumb($obj['id']);
                for ($i = 0; $i < count($arr['galleries']); $i++) {
                    if ($arr['galleries'][$i]['fname'] == '') {
                        $arr['galleries'][$i]['fpath'] = self::getPreview($arr['galleries'][$i]['id']);
                    } else {
                        $arr['galleries'][$i]['fpath'] = $arr['galleries'][$i]['id'] . '/thumb/' . $arr['galleries'][$i]['fname'];
                    }
                }

                //die(var_dump($arr['galleries']));

                $placeholdersCount = array(':id' => $this->id);
                $cnt = Database::selectQuery(self::SQL_GET_ALL_IMAGES_COUNT, $placeholdersCount, true);
                $arr['pages'] = ceil($cnt['cnt'] / $perpage);
                $arr['curPage'] = $page;
                $arr['preUrl'] = '/gallery/' . $this->rewrite . '/';
            } else {
                $arr['type'] = 'galleries';
                $arr['galleries'] = self::galleryList();
            }
        } else {
            $arr['type'] = 'galleries';
            $arr['breadcrumb'] = [];
            $arr['galleries'] = self::galleryList();
        }
        return $arr;
    }
    
    function galleryList($gal_id = 0)
    {
        $placeholders = array(':gal_id' => $gal_id);
        $rows = Database::selectQuery(self::SQL_GET_GAL_BY_PARENT, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['name'] = $rows;
        }

        return $rows;
    }

    /**
     * For admin's panel tree combo input contents generation.
     * 
     */
    static public function getGalleryHierarchy($cat_id = 0, $level = 0)
    {

        $prefix = '';
        for ($i = 0; $i < $level; $i++) {
            $prefix .= '--';
        }

        $arr = [];
        if ($level == 0) {
            $arr[] = array('id' => 0, 'name' => '-- --');
        }

        $placeholders = array(':gallery_id' => $cat_id);
        $objs = Database::selectQuery(self::SQL_LOAD_SUBGALLERY_ORD, $placeholders);
        foreach ($objs as $row) {
            $arr[] = array('id' => $row['id'], 'name' => $prefix . ' ' . $row['name']);
            $tmp_arr = self::getGalleryHierarchy($row['id'], ($level + 1));
            $arr = array_merge($arr, $tmp_arr);
        }
        return $arr;
    }

    static public function combo($id)
    {
        return array(
            'options' => self::getGalleryHierarchy(),
            'value' => $id
            );
    }        
    
    static public function idToName($id)
    {
        if (!is_numeric($id)) {
            return;
        }
      
        $placeholders = array(':id' => $id);
        $obj = Database::selectQuery(self::SQL_GET_BY_ID, $placeholders);

        if (isset($obj['name'])) {
            return $obj['name'];
        } else {
            return '';
        }
    }
    
    public static function getRndIds($limit = 4)
    {
        $placeholders = array(
            ':limit' => array('type' => 'int', 'value' => $limit),
            );
        $objs = Database::selectQueryBind(self::SQL_RANDOM_IMGS, $placeholders);
        return $objs;
    
    }

    protected function _postSave($id, $arr = '', $isInserted = true)
    {
        $this->uploadImages($id);
    }

    public function getById($id)
    {
        $placeholders = [':id' => $id];
        $row = Database::selectQuery(self::SQL_GET_BY_ID, $placeholders, true);
        return $row;
    }
}
