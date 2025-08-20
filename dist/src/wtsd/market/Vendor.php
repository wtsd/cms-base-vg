<?php
namespace wtsd\market;

use wtsd\common;
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
class Vendor extends ProtoClass
{

    const SQL_BY_ID = 'SELECT * FROM `tblVendor` WHERE `id` = :id LIMIT 1',
            SQL_LOAD_BY_REWRITE = 'SELECT `v`.* FROM `tblVendor` `v` WHERE `v`.`rewrite` = :rewrite LIMIT 1',
            SQL_ALL = 'SELECT * FROM `tblVendor` WHERE `status` = 1';

    public $_table = 'tblVendor';
    protected $_imagesTable = 'tblVendorImages';
    protected $_imagesFk = 'vendor_id';
    protected $_imagesDir = '/img/vendor/';

    protected $c_type = 'vendor';

    public static $imgdir = '/img/vendor/';

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('descr', 'textarea');
        $this->addField('status', 'cmb', true);
        $this->addField('cdate', 'time');
        $this->addField('site');

        parent::__construct($id);
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
            'rewrite' => '',
            'descr' => '',
            'status' => '1',
            'cdate' => date('Y-m-d H:i:s'),
            'site' => '',

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    public static function idToName($id)
    {
        $placeholders = array(':id' => $id);
        $row = Database::selectQuery(self::SQL_BY_ID, $placeholders, true);

        return $row['name'];
    }

    public static function loadFromRewrite($rewrite)
    {

        $placeholders = array(':rewrite' => $rewrite);
        $ret = Database::selectQuery(self::SQL_LOAD_BY_REWRITE, $placeholders, true);

        return $ret;
    }
    
    public static function getByPCat($pcat_id)
    {
        $pcat = new PCategory($pcat_id);
        $arr = $pcat->getParents();
        if ($pcat_id > 0) {
            $sql = "SELECT * FROM `tblVendor` WHERE `pcat_id` = :pcat_id";
            $placeholders = array(':pcat_id' => $pcat_id);
            for ($i = 0; $i < count($arr); $i++) {
                if ($arr[$i]['id'] !== $pcat_id) {
                    $sql .= " OR `pcat_id` = :pcat_id_" . $i;
                    $placeholders[':pcat_id_' . $i] = $arr[$i]['id'];
                }
            }
            $rows = Database::selectQuery($sql, $placeholders);
        } else {
            $rows = [];
        }
        return $rows;
    }
    
}