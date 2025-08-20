<?php
namespace wtsd\market;

use wtsd\common;
use PDO;
use wtsd\common\ProtoClass;
use wtsd\market\PCategory;
use wtsd\market\PSpec;
use wtsd\common\Database;
use wtsd\common\Text;
use wtsd\common\Site;
use wtsd\common\Template;
use wtsd\common\Register;
/**
* Defines the offer entity, which is the representation of the products
* those are provided by the shop.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Offer extends ProtoClass
{

    const SQL_BY_ID = 'SELECT * FROM `tblOffer` WHERE `id` = :id LIMIT 1',
            SQL_COUNT = 'SELECT count(`o`.`id`) AS `cnt` FROM `tblOffer` `o` LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id` INNER JOIN `tblPCategory` `pc` ON `pc`.`id` = `op`.`pcat_id` WHERE `o`.`status` = 1',
            SQL_GET_TOP = 'SELECT
                `o`.*,
                `pc`.`rewrite` AS `pcat_rewrite`,
                `pc`.`name` AS `pcat_name`,
                `oi`.`fname` AS `photo`,
                `pc`.`id` AS pcat_id
                FROM `tblOffer` `o`
                LEFT JOIN `tblOfferPcat` `opc` ON `opc`.`offer_id` = `o`.`id`
                LEFT JOIN `tblPCategory` `pc` ON `pc`.`id` = `opc`.`pcat_id`
                LEFT JOIN `tblOfferImages` `oi` ON `o`.`id` = `oi`.`offer_id` AND `oi`.`is_main` = 1
                WHERE `o`.`status` = 1 %s 
            GROUP BY `o`.`id`
            ORDER BY `o`.`id` DESC LIMIT :off, :count',
            SQL_GET_SPECIAL = 'SELECT
                `o`.*,
                `o`.`rewrite` AS `rewrite`,
                `pc`.`rewrite` AS `pcat_rewrite`,
                `pc`.`name` AS `pcat_name`,
                `pc`.`id` AS `pcat_id`,
                `oi`.`fname` AS `photo`
            FROM `tblOffer` `o` 
            INNER JOIN `tblPCategory` pc ON `pc`.`id` = `o`.`pcat_id` 
            LEFT JOIN `tblOfferImages` `oi` ON `o`.`id` = `oi`.`offer_id` 
            WHERE 
                `o`.`status` = 1 
                AND `o`.`is_special` = 1 
                AND `oi`.`is_main` = 1
            -- GROUP BY `o`.`id` 
            ORDER BY `o`.`cdate` DESC LIMIT :off, :count',
            SQL_LOAD_FROM_PCAT = 'SELECT `o`.*, `pc`.`rewrite` AS `pcat_rewrite`, `pc`.`id` AS `pcat_id` FROM `tblOffer` `o` LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id`  INNER JOIN `tblPCategory` `pc` ON `pc`.`id` = `o`.`pcat_id` WHERE `o`.`status` = 1 AND `op`.`pcat_id` = :pcat_id ORDER BY `o`.`cdate` DESC LIMIT :off, :count',
            SQL_LOAD_BY_REWRITE = '
                SELECT
                    `o`.*, `oi`.`fname` AS `photo`, `pc`.`rewrite` AS `pcat_rewrite`, `pc`.`name` AS `pcat_name`, `pc`.`post_desc` AS `post_desc`, `v`.`name` AS `vendor_name`, `v`.`site` AS `vendor_site`, `v`.`rewrite` AS `vendor_rewrite` 
                    FROM `tblOffer` `o` 
                    LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id`  
                    LEFT JOIN `tblPCategory` `pc` ON `op`.`pcat_id` = `pc`.`id` 
                    LEFT JOIN `tblOfferImages` `oi` ON `o`.`id` = `oi`.`offer_id` AND `oi`.`is_main` = 1
                    LEFT JOIN `tblVendor` `v` ON `v`.`id` = `o`.`vendor_id`
                    WHERE `o`.`status` = 1 AND `o`.`rewrite` = :rewrite  LIMIT 1',
            SQL_GET_RECOMMENDED = 'SELECT `o`.*, `pc`.`rewrite` AS `pcat_rewrite`, `pc`.`name` AS `pcat_name`, `pc`.`id` AS `pcat_id` FROM `tblOffer` `o` LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id`  INNER JOIN `tblPCategory` pc ON `pc`.`id` = `op`.`pcat_id` WHERE `o`.`status` = 1 AND `o`.`price` > 0 AND `o`.`is_recommended` = 1 LIMIT :count',
            SQL_INSERT_IMAGES = "INSERT INTO `tblOfferImages` (`fname`, `cdate`, `offer_id`) VALUES (:fname, Now(), :offer_id)",
            SQL_LOAD_IMAGES = "SELECT * FROM `tblOfferImages` WHERE `offer_id` = :offer_id",
            SQL_FIND = 'SELECT `o`.*, `pc`.`rewrite` AS `pcat_rewrite`, `pc`.`name` AS `pcat_name`, `pc`.`id` AS pcat_id, `oi`.`fname` AS `photo` FROM `tblOffer` `o` LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id`  INNER JOIN `tblPCategory` `pc` ON `pc`.`id` = `op`.`pcat_id` LEFT JOIN `tblOfferImages` `oi` ON `o`.`id` = `oi`.`offer_id` WHERE     `o`.`status` = 1 AND (`o`.`name` LIKE :name)
                AND `oi`.`is_main` = 1
             ORDER BY `o`.`cdate` DESC LIMIT :off, :count',
            SQL_FIND_PCATS = 'SELECT `pc`.`rewrite` AS `rewrite`, `pc`.`name` AS `name`, `pc`.`id` AS pcat_id FROM `tblOffer` `o` LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id`  INNER JOIN `tblPCategory` `pc` ON `pc`.`id` = `op`.`pcat_id` WHERE `o`.`status` = 1 AND (`o`.`name` LIKE :name) GROUP BY `pc`.`id`',
            SQL_FIND_CNT = 'SELECT count(*) AS `cnt` FROM `tblOffer` `o` WHERE `o`.`status` = 1 AND (`o`.`name` LIKE :name)',
            SQL_COUNTBY = "SELECT count(*) AS `cnt` FROM `tblOffer` `o` LEFT JOIN `tblUser` `u` ON `u`.`id` = `o`.`user_id` LEFT JOIN `tblPCategory` `pc` ON `pc`.`id` = `o`.`pcat_id` WHERE %s",
            SQL_BY_VENDOR = 'SELECT `o`.*, `pc`.`rewrite` AS `pcat_rewrite`, `pc`.`name` AS `pcat_name`, `pc`.`id` AS pcat_id, `oi`.`fname` AS `photo` FROM `tblOffer` `o` LEFT JOIN `tblOfferPcat` `op` ON `op`.`offer_id` = `o`.`id`  INNER JOIN `tblPCategory` `pc` ON `pc`.`id` = `op`.`pcat_id` LEFT JOIN `tblOfferImages` `oi` ON `o`.`id` = `oi`.`offer_id` WHERE `o`.`status` = 1 AND `o`.`vendor_id` = :vendor_id AND `oi`.`is_main` = 1 ';
            
    public $id, $pcat_id, $name, $descr, $price = 0, $is_special, $status, $cdate, $mdate, $user_id, $rewrite, $comment, $breadcrumb;

    public $_table = 'tblOffer';
    protected $imagesTable = 'tblOfferImages';
    protected $imagesFk = 'offer_id';
    protected $imagesDir = '/img/offer/';
    protected $foreignKey = 'offer_id';

    protected $c_type = 'offer';


    public static $imgdir = '/img/offer/';

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        //$this->addField('pcat_ids', 'cmb');
        $this->addField('name', 'text', true, true);
        $this->addField('rewrite', 'text', true, true);
        $this->addField('price');
        $this->addField('descr', 'textarea');
        $this->addField('vendor_id', 'cmb');
        $this->addField('is_special', 'checkbox');
        $this->addField('cdate', 'time');
        $this->addField('mdate', 'time', false, false);
        $this->addField('status', 'cmb', true);
        $this->addField('comment', 'textarea');
        $this->addField('ord');
        $this->addField('is_recommended', 'checkbox');

        $this->addField('h1');
        $this->addField('h2');
        $this->addField('meta_keywords');
        $this->addField('meta_description');
        $this->addField('title');


        if (is_numeric($id)) {
            parent::__construct($id);
        } elseif ($id !== '') {
            $rewrite = $id;
            $arr = self::loadFromRewrite($rewrite);
            if (isset($arr['id'])) {
                parent::__construct($arr['id']);
            }
        }
        $this->price_label = number_format($this->price, 0, ',', ' ') . Text::numRussification($this->price, " рубль", " рублей", " рублей");

    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'pcat_id' => '0',
            'name' => '',
            'rewrite' => '',
            'price' => '0',
            'descr' => '',
            'vendor_id' => '0',
            'is_special' => '0',
            'cdate' => date('Y-m-d H:i:s'),
            'mdate' => date('Y-m-d H:i:s'),
            'status' => '1',
            'comment' => '',
            'ord' => '0',
            'is_recommended' => '0',

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            'pcat_ids' => array(),
            'pcats' => array(),
            );
    }


    public function getById($id)
    {
        if ($id > 0) {
            $queryBuilder = Database::getQueryBuilder();
            $queryBuilder->select('*')
                ->from('tblOffer')
                ->where('`id` = ' . $queryBuilder->createNamedParameter($id))
                ->setMaxResults(1);

            $row = $queryBuilder->execute()
                ->fetch(\PDO::FETCH_ASSOC);

            if (!isset($row['id'])) {
                return $this->getEmpty();
            }
            $row['pcats'] = $this->getPCats($id, true);
            return $row;
        } else {
            return $this->getEmpty();
        }

    }



    protected function _getRecords($off, $perp, $sort = null, $filter = null)
    {

        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':perpage' => array('type' => 'int', 'value' => $perp),
            );
        $sql_wh = '';
        if ($filter['q'] !== null) {
            $sql_wh .= ' AND `o`.`name` LIKE :q';
            $placeholders[':q'] = array('type' => 'string', 'value' => '%'.$filter['q'].'%');
        }     
        if (intval($filter['pcat_id']) > 0) {
            $sql_wh .= ' AND `pc`.`pcat_id` = :pcat_id';
            $placeholders[':pcat_id'] = array('type' => 'int', 'value' => $filter['pcat_id']);
        }     

        $ord = '';
        if (in_array($filter['sortby'], ['id', 'name', 'price', 'cdate']) && in_array($filter['sortdir'], ['asc', 'desc'])) {
            $ord = " ORDER BY `o`.`{$filter['sortby']}` {$filter['sortdir']}";
        }

        $sql = "SELECT `o`.*, `oi`.`fname`, `v`.`name` AS `vendor_name`, `u`.`name` AS `username`
            FROM `tblOffer` `o` 
            LEFT JOIN `tblVendor` `v` ON `v`.`id` = `o`.`vendor_id`
            LEFT JOIN `tblUser` `u` ON `u`.`id` = `o`.`user_id`
            LEFT JOIN `tblOfferImages` `oi` ON `oi`.`offer_id` = `o`.`id` LEFT JOIN `tblOfferPcat` `pc` ON `pc`.`offer_id` = `o`.`id` WHERE 1 {$sql_wh}  GROUP BY `o`.`id` {$ord} LIMIT :off, :perpage";
        $rows = Database::selectQueryBind($sql, $placeholders);


        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['images'] = $this->getPhotos($rows[$i]['id']);
        }
        
        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['pcats'] = $this->getPCats($rows[$i]['id'], false);
        }
        return $rows;
    }

    protected function getPCats($offer_id, $simple = false)
    {
        $sql = "SELECT *, `p`.`rewrite` AS `pcat_rewrite`, `p`.`name` AS `pcat_name`, `p`.`id` AS `pcat_id` FROM `tblOfferPcat` `op` INNER JOIN `tblPCategory` `p` ON `p`.`id` = `op`.`pcat_id` WHERE `op`.`offer_id` = :offer_id";
        $placeholders = array(':offer_id' => $offer_id);
        $rows = Database::selectQuery($sql, $placeholders);

        if ($simple) {
            for ($i = 0; $i < count($rows); $i++) {
                $rows[$i] = $rows[$i]['pcat_id'];
            }
        }

        return $rows;
    }

    function getBreadcrumb()
    {
        $pcats = array();
        $i = 0;
        $pcats[$i] = new PCategory($this->pcat_id);
        while ($pcats[$i]->pcat_id != 0) {
            $i++;
            $pcats[$i] = new PCategory($pcats[$i-1]->pcat_id);
        }
        
        $this->breadcrumb = $pcats;
        
        //return $this->breadcrumb;
        $html = 'Каталог';
        for ($j = $i; $j >= 0; $j--) {
            $html .= ' <a href="#">' . $pcats[$j]->name . '</a>';
        }
        return $html;
        
    }
    
    function idToName($id) 
    {
        if (!is_numeric($id)) {
            return;
        }
      
        $placeholders = array(':id' => $id);

        $row = Database::selectQuery(self::SQL_BY_ID, $placeholders);    
        if (isset($row['name'])) {
            return htmlspecialchars($row['name']);
        } else {
            return '/';
        }
    }

    public function lst($page = 1, $filters = null)
    {
        $pages = 0;
        $q = $filters['q'];
        if ($filters['pcat_id'] == '') {
            $cnt = ($q === null) ? $this->getAllCount() : $this->getCountSearch($filters['q']);
        } else {
            $pcategory = new \wtsd\market\PCategory($filters['pcat_id']);
            $pcat = $pcategory->load($filters['pcat_id']);
            $cnt = $this->getCount($pcat['rewrite']);
        }

        $records = [];
        if ($cnt > 0) {
            $pages = floor($cnt / $this->_perPage) + 1;

            $off = intval($this->_perPage * ($page - 1));
            $perp = intval($this->_perPage);

            $sort = [
                'by' => $filters['sortby'],
                'method' => $filters['sortdir'],
            ];
            $records = $this->_getRecords($off, $perp, $sort, $filters);
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

    public function countBy($type, $id = null)
    {
        $sqlWhere = "1";

        if ($type == 'search') {
            $sqlQuery = '%' . $id . '%';
            $sqlWhere = '`o`.`name` LIKE :query1';
            $placeholders[':query1'] = array('type' => 'text', 'value' => $sqlQuery);
        }

        $sql = sprintf(self::SQL_COUNTBY, $sqlWhere);
        $row = Database::selectQueryBind($sql, $placeholders, true);

        return $row['cnt'];
    }

    static public function getAllCount() {
        $row = Database::selectQuery(self::SQL_COUNT, [], true);
        return $row['cnt'];
    }

    static public function getCount($rewrite = '')
    {
        
        $sql_where = '';
        $placeholders = array();

        if ($rewrite != '') {
            $sql_where = ' AND `pc`.`rewrite` = :rewrite ';
            $placeholders[':rewrite'] = array('type' => 'int', 'value' => $rewrite);
        }

        $sql = self::SQL_COUNT . $sql_where;
        $objs = Database::selectQueryBind($sql, $placeholders, true);

        return $objs['cnt'];
    }


    public function getCountSearch($filter)
    {
        return $this->countBy('search', $filter);
    }

    public function getByVendor($vendor_id)
    {
        $placeholders = array(':vendor_id' => $vendor_id);
        $objs = Database::selectQuery(self::SQL_BY_VENDOR, $placeholders);

        if (count($objs) > 0) {
            for ($i = 0; $i < count($objs); $i++) {
                $objs[$i]['price_label'] = $this->formatPrice($objs[$i]['price']);
                $objs[$i]['pcats'] = $this->getPCats($objs[$i]['id']);
            }
        }
        
        return $objs;
    }

    public function getTop($rewrite = '', $count = 10, $off = 0)
    {
        $sql_rewrite = '';
        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':count' => array('type' => 'int', 'value' => intval($count)),
            );
        if ($rewrite != '') {
            $sql_rewrite = ' AND `pc`.`rewrite` = :rewrite ';
            $placeholders[':rewrite'] = array('type' => 'string', 'value' => $rewrite);
        }
        $sql = sprintf(self::SQL_GET_TOP, $sql_rewrite);
        $objs = Database::selectQueryBind($sql, $placeholders);

        if (count($objs) > 0) {
            for ($i = 0; $i < count($objs); $i++) {
                if ($objs[$i]['price'] > 0) {
                    $objs[$i]['price_label'] = number_format($objs[$i]['price'], 0, ',', ' ') . Text::numRussification($objs[$i]['price'], " рубль", " рублей", " рублей");

                    $objs[$i]['pcats'] = $this->getPCats($objs[$i]['id']);
                } else {
                    $objs[$i]['price_label'] = 'Нет в наличии';
                }
            }
        }

        return $objs;
    }

    public function getSpecial($count = 10, $off = 0)
    {
        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => intval($off)),
            ':count' => array('type' => 'int', 'value' => intval($count)),
            );
        $objs = Database::selectQueryBind(self::SQL_GET_SPECIAL, $placeholders);
        if (count($objs) > 0) {
            for ($i = 0; $i < count($objs); $i++) {
                if ($objs[$i]['price'] > 0) {
                    $objs[$i]['price_label'] = number_format($objs[$i]['price'], 0, ',', ' ') . Text::numRussification($objs[$i]['price'], " рубль", " рублей", " рублей");
                    $objs[$i]['pcats'] = $this->getPCats($objs[$i]['id']);

                } else {
                    $objs[$i]['price_label'] = 'Нет в наличии';
                }
            }
        }

        return $objs;
    }
    
    static function getPCatContents($id)
    {
        
        $offers = array();
        $html_offers = $html_pcats = '';
        $pcat = new PCategory($id);
        
        
        $offers = self::getFromPCategory($id);

        $pcats = $pcat->getChildren();
        $assigned = array(
            'html_offers' => $html_offers,
            'pcats' => object_to_array($pcats),
            'offers' => object_to_array($offers),
            'title' => $pcat->name
            );
        $tpl = 'pcategory.tpl';

        $view = new Template();
        $view->assignAll($assigned);
        return $view->render($tpl);
        
    }
    
    static function getFromPCategory($id = 0, $count = 10, $off = 0)
    {
        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':count' => array('type' => 'int', 'value' => $count),
            ':pcat_id' => array('type' => 'int', 'value' => $id),
            );
        $objs = Database::selectQueryBind(self::SQL_LOAD_FROM_PCAT, $placeholders);


        for ($i = 0; $i < count($objs); $i++) {
            $objs[$i]['pcats'] = $this->getPCats($objs[$i]['id']);
        }

        return $objs;
    }

    public static function showCatalogue($rewrite, $offset = 1)
    {
        return PCategory::buildTree(PCategory::getPCats(0), $rewrite, $offset);
        
    }
    
    public function loadById($id)
    {
        $placeholders = array(':id' => $id);
        $ret = Database::selectQuery(self::SQL_BY_ID, $placeholders, true);

        $ret['images'] = $this->getPhotos($ret['id']);
        if ($ret['price'] > 0) {
            $ret['price_label'] = Text::rusCurrency($ret['price']);
        }

        $ret['specs'] = $this->getSpecs($ret['id']);

        $ret['pcats'] = $this->getPCats($ret['id']);

        $ret['comments'] = $this->loadComments($ret['id']);
        $ret['comment_count'] = $this->countComments($ret['id']);
        $ret['cmntpages'] = floor($ret['comment_count']/10);

        return $ret;
    }

    public function loadFromRewrite($rewrite)
    {
        $config = \wtsd\common\Register::get('config');
        try {
            $placeholders = array(':rewrite' => $rewrite);
            $ret = Database::selectQuery(self::SQL_LOAD_BY_REWRITE, $placeholders, true);
            if ($ret) {

                $ret['images'] = $this->getPhotos($ret['id']);
                if ($ret['price'] > 0) {
                    $ret['price_label'] = Text::rusCurrency($ret['price']);
                }


                $ret['pcats'] = $this->getPCats($ret['id']);
                $ret['specs'] = $this->getSpecs($ret['id']);
    
                $ret['comments'] = $this->loadComments($ret['id']);
                $ret['comment_count'] = $this->countComments($ret['id']);
                $ret['cmntpages'] = floor($ret['comment_count']/10);

            } else {
                throw new \Exception('Not found!');
                return false;
            }

            return $ret;
        } catch (\Exception $e) {
            return array('error' => 'Not found!');
        }
    }

    public function saveComment($comment, $name, $fid, $ip, $type = 'offer')
    {
        return \wtsd\misc\Comment::save($comment, $name, $fid, $ip, 'offer');
    }

    public function countComments($fid, $type = 'offer')
    {
        return \wtsd\misc\Comment::count($fid, 'offer');
    }

    public function loadComments($fid, $count = 10, $page = 1, $type = 'offer')
    {
        return \wtsd\misc\Comment::load($fid, 'offer', $count, $page);   
    }

    public function _preSave()
    {
        $translit = Text::transliterate($this->name);
        $this->rewrite = strtolower(str_replace(' ', '-', $translit));
    }

    public function getPrice()
    {
        return $this->price;
    }

    static public function getRecommended($count = 4)
    {
        $placeholders = array(
            ':count' => array('type' => 'int', 'value' => $count),
            );
        $objs = Database::selectQueryBind(self::SQL_GET_RECOMMENDED, $placeholders);
        if (count($objs) > 0) {
            for ($i = 0; $i < count($objs); $i++) {
                if ($objs[$i]['price'] > 0) {
                    $objs[$i]['price_label'] = number_format($objs[$i]['price'], 0, ',', ' ') . Text::numRussification($objs[$i]['price'], " рубль", " рублей", " рублей");

                    $objs[$i]['pcats'] = $this->getPCats($objs[$i]['id']);
                } else {
                    $objs[$i]['price_label'] = 'Нет в наличии';
                }
            }
        }

        return $objs;
    }


    public function load($id = null)
    {
        if (!$id && $this->id) {
            $id = $this->id;
        }
      $queryBuilder = Database::getQueryBuilder();
        
        $queryBuilder->select('`o`.*')
            ->from('tblOffer', 'o')
            ->where('`o`.`id` = '. $queryBuilder->createNamedParameter($id));

        $row = $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);

        if (count($row) == 0) {
            throw new \Exception('Offer not found!');
        }

        if ($row) {
            foreach ($row as $field => $val) {
                $this->$field = $val;
            }
        }

        return $row;
    }

    public function save($arr)
    {

        if (!isset($arr['pcat_ids'])) {
            return array(
                'status' => 'error',
                'msg' => 'Нужно выбрать категорию',
                );
        }

        $placeholders = [
            ':cdate' => $arr['cdate'],
            ':name' => $arr['name'],
            ':descr' => $arr['descr'],
            ':h1' => $arr['h1'],
            ':h2' => $arr['h2'],
            ':meta_keywords' => $arr['meta_keywords'],
            ':meta_description' => $arr['meta_description'],
            ':title' => $arr['title'],
            ':comment' => $arr['comment'],
            ':ord' => $arr['ord'],
            ':price' => $arr['price'],
            ':rewrite' => $arr['rewrite'],
            ':vendor_id' => $arr['vendor_id'],
            ':status' => $arr['status'],

        ];

        try {
            $queryBuilder = Database::getQueryBuilder();
            $queryBuilder->select('*')
                ->from('tblOffer')
                ->where('`rewrite` = ' . $queryBuilder->createNamedParameter($arr['rewrite']));
            if ($arr['id'] > 0) {
                $queryBuilder->andWhere('`id` != ' . $queryBuilder->createNamedParameter($arr['id']))
                    ;
                $rowVal = $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);
                if (isset($rowVal['id'])) {
                    return [
                        'status' => 'error',
                        'msg' => 'Товар с таким rewrite уже существует',
                    ];
                }

                $placeholders[':id'] = $arr['id'];
                $this->update($placeholders);
                $id = $arr['id'];
                //$this->load($arr['id']);
            }
            if ($arr['id'] == 0) {
                $rowVal = $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);
                if (count($rowVal) > 0) {
                    return [
                        'status' => 'error',
                        'msg' => 'Товар с таким rewrite уже существует',
                    ];
                }

                $user = \wtsd\common\Factory::create('User');
                $placeholders[':user_id'] = $user->getId();
                $id = $this->insert($placeholders);
            }


            $sqlDelete = "DELETE FROM `tblOfferPcat` WHERE `offer_id` = :offer_id";
            Database::deleteQuery($sqlDelete, array(':offer_id' => $arr['id']));

            foreach ($arr['pcat_ids'] as $pcat_id)
            {
                $sql = "SELECT * FROM `tblPSpec` WHERE `pcat_id` = :pcat_id";
                $placeholders = array(':pcat_id' => $pcat_id);
                $rows = Database::selectQuery($sql, $placeholders);
                $specs = [];
                foreach ($rows as $row) {
                    if (isset($arr['spec_' . $row['id']])) {
                        $specs[$row['id']] = $arr['spec_' . $row['id']];
                    }
                }
                $sqlDelete = "DELETE FROM `tblPSpecVal` WHERE `offer_id` = :offer_id";
                Database::deleteQuery($sqlDelete, array(':offer_id' => $arr['id']));

                foreach ($specs as $pspec_id => $val) {
                    $sqlUpdate = "INSERT INTO `tblPSpecVal` (`pspec_id`, `offer_id`, `val`) VALUES (:pspec_id, :offer_id, :val) ON DUPLICATE KEY UPDATE `val` = :val2";
                    $updatePlaceholders = array(':pspec_id' => $pspec_id, ':offer_id' => $arr['id'], ':val' => $val, ':val2' => $val);
                    Database::insertQuery($sqlUpdate, $updatePlaceholders);
                }
                
                $sqlUpdate = "INSERT INTO `tblOfferPcat` (`pcat_id`, `offer_id`) VALUES (:pcat_id, :offer_id)";
                $updatePlaceholders = array(':pcat_id' => $pcat_id, ':offer_id' => $arr['id']);
                Database::insertQuery($sqlUpdate, $updatePlaceholders);
            }

            $this->uploadImages($id);
            $this->setMainImage($id);


            $labels = Register::get('lang', 'admin');
            return [
                'status' => 'ok',
                'msg' => $labels['msgs']['all_saved'] . ' ' . $id,
                'id' => $id,
                'errors' => [],
            ];
        } catch (\PDOException $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage(),
            ];
        }


        /* Saving specs */


        return $json;

    }



    public function update($placeholders)
    {

        $queryBuilder = Database::getQueryBuilder();
        $queryBuilder->update('tblOffer')
            ->where('`id` = '. $queryBuilder->createNamedParameter($placeholders[':id']));

        if (isset($placeholders[':cdate'])) {
            $queryBuilder->set('`cdate`', $queryBuilder->createNamedParameter($placeholders[':cdate']));
        }
        if (isset($placeholders[':name'])) {
            $queryBuilder->set('`name`', $queryBuilder->createNamedParameter($placeholders[':name']));
        }
        if (isset($placeholders[':descr'])) {
            $queryBuilder->set('`descr`', $queryBuilder->createNamedParameter($placeholders[':descr']));
        }
        if (isset($placeholders[':h1'])) {
            $queryBuilder->set('`h1`', $queryBuilder->createNamedParameter($placeholders[':h1']));
        }
        if (isset($placeholders[':h2'])) {
            $queryBuilder->set('`h2`', $queryBuilder->createNamedParameter($placeholders[':h2']));
        }
        if (isset($placeholders[':meta_keywords'])) {
            $queryBuilder->set('`meta_keywords`', $queryBuilder->createNamedParameter($placeholders[':meta_keywords']));
        }
        if (isset($placeholders[':meta_description'])) {
            $queryBuilder->set('`meta_description`', $queryBuilder->createNamedParameter($placeholders[':meta_description']));
        }
        if (isset($placeholders[':title'])) {
            $queryBuilder->set('`title`', $queryBuilder->createNamedParameter($placeholders[':title']));
        }
        if (isset($placeholders[':comment'])) {
            $queryBuilder->set('`comment`', $queryBuilder->createNamedParameter($placeholders[':comment']));
        }
        if (isset($placeholders[':ord'])) {
            $queryBuilder->set('`ord`', $queryBuilder->createNamedParameter($placeholders[':ord']));
        }
        if (isset($placeholders[':price'])) {
            $queryBuilder->set('`price`', $queryBuilder->createNamedParameter($placeholders[':price']));
        }
        if (isset($placeholders[':rewrite'])) {
            $queryBuilder->set('`rewrite`', $queryBuilder->createNamedParameter($placeholders[':rewrite']));
        }
        if (isset($placeholders[':vendor_id'])) {
            $queryBuilder->set('`vendor_id`', $queryBuilder->createNamedParameter($placeholders[':vendor_id']));
        }
        if (isset($placeholders[':status'])) {
            $queryBuilder->set('`status`', $queryBuilder->createNamedParameter($placeholders[':status']));
        }

        
        $queryBuilder->execute();
    }
    public function insert($placeholders)
    {

        $queryBuilder = Database::getQueryBuilder();        
        $queryBuilder->insert('tblOffer')
            ->values(
                [
                '`user_id`' => $queryBuilder->createNamedParameter($placeholders[':user_id']),

                'cdate' => $queryBuilder->createNamedParameter($placeholders[':cdate']),
                'name' => $queryBuilder->createNamedParameter($placeholders[':name']),
                'descr' => $queryBuilder->createNamedParameter($placeholders[':descr']),
                'h1' => $queryBuilder->createNamedParameter($placeholders[':h1']),
                'h2' => $queryBuilder->createNamedParameter($placeholders[':h2']),
                'meta_keywords' => $queryBuilder->createNamedParameter($placeholders[':meta_keywords']),
                'meta_description' => $queryBuilder->createNamedParameter($placeholders[':meta_description']),
                'title' => $queryBuilder->createNamedParameter($placeholders[':title']),
                'comment' => $queryBuilder->createNamedParameter($placeholders[':comment']),
                'ord' => $queryBuilder->createNamedParameter($placeholders[':ord']),
                'price' => $queryBuilder->createNamedParameter($placeholders[':price']),
                'rewrite' => $queryBuilder->createNamedParameter($placeholders[':rewrite']),
                'vendor_id' => $queryBuilder->createNamedParameter($placeholders[':vendor_id']),
                'status' => $queryBuilder->createNamedParameter($placeholders[':status']),

                ]
                )
            ;

        try {
            $queryBuilder->execute();
            return Database::lastId();

        } catch (\PDOException $e) {
            return 
                [
                'status' => 'error',
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                ];
        } catch (\Exception $e) {
            return 
                [
                'status' => 'error',
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                ];
        }
    }

    protected function _postSave($id, $arr = '', $isInserted = true)
    {
        $this->uploadImages($id);
        $this->setMainImage($id);
    }

    public function setMainImage($offerId, $imgId = 0) {
        $sqlReset = "UPDATE `tblOfferImages` SET `is_main` = 0 WHERE `offer_id` = :offer_id";
        Database::updateQuery($sqlReset, [':offer_id' => $offerId]);
        if ($imgId > 0) {
            $sqlSet = "UPDATE `tblOfferImages` SET `is_main` = 1 WHERE `id` = :id";
            Database::updateQuery($sqlSet, [':id' => $imgId]);
        } else {
            $sqlImgId = "SELECT `id` FROM `tblOfferImages` ORDER BY `id` DESC LIMIT 1";
            $row = Database::selectQuery($sqlImgId, [':offer_id' => $offerId], true);
            if (isset($row['id'])) {
                $sqlSet = "UPDATE `tblOfferImages` SET `is_main` = 1 WHERE `id` = :id";
                Database::updateQuery($sqlSet, [':id' => $row['id']]);
            }
        }
    }
    public static function getSpecs($id)
    {
        
        if (!is_numeric($id)) {
            return;
        }

        $sql = "SELECT * FROM `tblPSpecVal` `psv` INNER JOIN `tblPSpec` `ps` ON `ps`.`id` = `psv`.`pspec_id` WHERE `psv`.`offer_id` = :offer_id";
        $placeholders = array(
            ':offer_id' => $id
            );
        $objs = Database::selectQuery($sql, $placeholders);

        return $objs;
    }

    static public function formatPrice($price)
    {
        $lang = Register::get('lang');
        if ($price > 0) {
            return number_format($price, 0, ',', ' ') . Text::numRussification($price, " рубль", " рублей", " рублей");
        } else {
            return $lang['market_no'];
        }
    }

    public function find($query = '', $count = 10, $off = 0)
    {
        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':count' => array('type' => 'int', 'value' => $count),
            ':name' => array('type' => 'string', 'value' => '%'.$query.'%'),
            //':descr' => array('type' => 'string', 'value' => '%'.$query.'%'),
            );

        $objs = Database::selectQueryBind(self::SQL_FIND, $placeholders);
        if (count($objs) > 0) {
            for ($i = 0; $i < count($objs); $i++) {
                $objs[$i]['price_label'] = $this->formatPrice($objs[$i]['price']);
            }
        }

        $placeholders_cnt = array(
            ':name' => array('type' => 'string', 'value' => '%'.$query.'%'),
            //':descr' => array('type' => 'string', 'value' => '%'.$query.'%'),
            );
        $cnt = Database::selectQueryBind(self::SQL_FIND_CNT, $placeholders_cnt, true)['cnt'];

        return $objs;
    }

    public function findPCats($query = '')
    {
        $placeholders = array(
            ':name' => array('type' => 'string', 'value' => '%'.$query.'%'),
            );

        $objs = Database::selectQueryBind(self::SQL_FIND_PCATS, $placeholders);

        return $objs;
    }

    static public function findPages($query = '', $perPage = 10)
    {
        
        $placeholders_cnt = array(
            ':name' => array('type' => 'string', 'value' => '%'.$query.'%'),
            //':descr' => array('type' => 'string', 'value' => '%'.$query.'%'),
            );
        $cnt = Database::selectQueryBind(self::SQL_FIND_CNT, $placeholders_cnt, true)['cnt'];

        return floor($cnt/$perPage);
    }

    static public function toggleStatus($id)
    {
        $obj = new self();
        $offer = $obj->loadById($id);

        if ($offer['status'] == 1) {
            $status = 0;
        } else {
            $status = 1;
        }

        $sql = "UPDATE `tblOffer` SET `status` = :status WHERE `id` = :id";
        Database::updateQuery($sql, [':status' => $status, ':id' => $id]);        
        return $status;
    }

    public function SetMainImageAjax($values)
    {
        $placeholders = [
            ':id' => $values['values']['id']
            ];
        $sqlReset = "UPDATE `tblOfferImages` SET `is_main` = 0 WHERE `offer_id` = :offer_id";
        Database::updateQuery($sqlReset, [':offer_id' => $values['values']['offer_id']]);

        $sqlSet = "UPDATE `tblOfferImages` SET `is_main` = 1 WHERE `id` = :id";
        Database::updateQuery($sqlSet, [':id' => $values['values']['id']]);
        return json_encode(array('status' => 'ok', 'msg' => 'Set main'));
    }
}
