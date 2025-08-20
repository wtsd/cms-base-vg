<?php
namespace wtsd\market;

use wtsd\common;
use PDO;
use wtsd\common\ProtoClass;
use wtsd\common\Database;
use wtsd\market\Offer;
use wtsd\market\PCategory;
use wtsd\common\Content\Image;
/**
* Defines the product category's specifiction.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class PSpec extends ProtoClass
{
    public $id, $name, $pcat_id, $stype;
    public $_table = 'tblPSpec';
    protected $c_type = 'pspec';

    const TYPE_TEXT = 0,
            TYPE_INT = 1,
            TYPE_CHECKBOX = 2,
            TYPE_LIST = 3,
            TYPE_COLOR = 4;

    static protected $_stypes = array(
                array('id' => self::TYPE_LIST, 'name' => 'Текстовое поле'),
                array('id' => self::TYPE_INT, 'name' => 'Числовое значение'),
                array('id' => self::TYPE_CHECKBOX, 'name' => 'Чекбокс'),
                array('id' => self::TYPE_LIST, 'name' => 'Список'),
                array('id' => self::TYPE_COLOR, 'name' => 'Цвет'),
                );

    protected $_fields = [];

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('pcat_id', 'cmb', true);
        $this->addField('stype', 'cmb', true, true);
        $this->addField('values');
        $this->addField('defval');
        $this->addField('required', 'checkbox');
        $this->addField('cdate', 'time');
        $this->addField('status', 'cmb', true);
        $this->addField('ord');

        parent::__construct($id);
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
            'pcat_id' => '0',
            'stype' => '',
            'values' => '',
            'defval' => '',
            'required' => '0',
            'cdate' => date('Y-m-d H:i:s'),
            'status' => '1',
            'ord' => '0',

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    public function getStypes()
    {
        return self::$_stypes;
    }

    protected function _getRecords($off, $perp, $sort = null)
    {
     
        $sql = sprintf("SELECT `ps`.*, `pc`.`name` AS `pcat_name` FROM `%s` `ps` INNER JOIN `tblPCategory` `pc` ON `pc`.`id` = `ps`.`pcat_id`", $this->_table);
        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':perpage' => array('type' => 'int', 'value' => $perp),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows;
    }

    static public function getSpecsByPCat($pcat_id)
    {
        $pcat = new PCategory($pcat_id);
        $arr = $pcat->getParents();
        if ($pcat_id > 0) {
            $sql = "SELECT * FROM `tblPSpec` WHERE `pcat_id` = :pcat_id";
            $placeholders = array(':pcat_id' => $pcat_id);
            for ($i = 0; $i < count($arr); $i++) {
                if ($arr[$i]['id'] !== $pcat_id) {
                    $sql .= " OR `pcat_id` = :pcat_id_" . $i;
                    $placeholders[':pcat_id_' . $i] = $arr[$i]['id'];
                }
            }
            $rows = Database::selectQuery($sql, $placeholders);

            for ($i = 0; $i < count($rows); $i++) {
                if ($rows[$i]['stype'] == PSpec::TYPE_LIST || $rows[$i]['stype'] == PSpec::TYPE_COLOR) {
                    $rows[$i]['values'] = array_map('trim', explode(',', $rows[$i]['values']));
                }
            }
        } else {
            $rows = [];
        }
        return $rows;
    }

    static public function getSpecValsByOffer($offer_id)
    {
        $sql = "SELECT * FROM `tblPSpecVal` WHERE `offer_id` = :offer_id";
        $placeholders = array(':offer_id' => $offer_id);
        $rows = Database::selectQuery($sql, $placeholders);

        $res = [];
        foreach ($rows as $row) {
            $res[$row['pspec_id']] = $row;
        }
        
        return $res;
    }

    static public function id2stype($id = '')
    {
        return self::$_stypes[$id]['name'];
    }

    static public function cmbSTypes($id = '')
    {
        return array(
            'options' => self::$_stypes,
            'value' => $id
            );
    }
}