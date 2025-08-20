<?php
namespace wtsd\content;

use wtsd\common;
use wtsd\common\Database;
use wtsd\common\ProtoClass;

class ArticleImage extends ProtoClass
{
    public $_table = 'tblArticleImages';
    protected $_imagesDir = '/img/article/';

    public function rotateAjax($args)
    {
        $id = $args['values']['id'];
        $galId = $args['values']['galleryId'];
        $dir = ROOT . $this->_imagesDir . $galId . '/';

        // @todo: Get filename
        $sql = "SELECT * FROM `tblArticleImages` WHERE `id` = :id LIMIT 1";
        $placeholders = array(
            ':id' => intval($id)
            );
        $row = Database::selectQuery($sql, $placeholders, true);
        $fname = $row['fname'];

        $this->imageRotate($dir . 'full/' . $fname);

        $this->imageResize($dir . 'full/' . $fname, 300, 200, $dir . 'thumb/' . $fname);

        return json_encode(array('status' => 'ok'));
    }
}