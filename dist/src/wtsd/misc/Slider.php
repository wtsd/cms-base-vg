<?php
namespace wtsd\misc;

use wtsd\common\Database;
use wtsd\common\ProtoClass;

class Slider extends ProtoClass
{

    public $_table = 'tblSlider';
    
    protected $imagesTable = 'tblSlide';
    protected $imagesFk = 'slider_id';
    protected $foreignKey = 'slider_id';
    protected $imagesDir = '/img/slider/';
    
    protected $c_type = 'slider';
    
    function __construct($id = '')
    {
        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('status', 'cmb', true);
        $this->addField('uri');

        parent::__construct($id);
    }

    protected function _getRecords($off, $perp, $sort = null, $filter = null)
    {

        $rows = parent::_getRecords($off, $perp, $sort, $filter);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['images'] = $this->getPhotos($rows[$i]['id']);
        }
        return $rows;
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
            'status' => '0',
            'uri' => '',
            );
    }

    public function getByUri($uri)
    {
        $uriSanitized = explode('?', $uri)[0];
        $sql = "SELECT * FROM `tblSlider` WHERE `uri` = :uri AND `status` = 1";
        $placeholders = array(':uri' => $uriSanitized);
        $rows = Database::selectQuery($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $sqlSlides = "SELECT * FROM `tblSlide` WHERE `slider_id` = :slider_id";
            $placeholdersSlides = array(':slider_id' => $rows[$i]['id']);
            $rows[$i]['slides'] = Database::selectQuery($sqlSlides, $placeholdersSlides);
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
        $fname_full = $dir . $row['fname'];
        unlink($fname_full);
        return json_encode(array('status' => 'error', 'row' => $row));
    }
}