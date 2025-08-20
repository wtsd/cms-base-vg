<?php
namespace wtsd\event;

use wtsd\common;
use wtsd\common\Database;
use wtsd\common\Register;
use wtsd\common\ProtoClass;
/**
* 
*
* @author   Vladislav Gafurov <warlockfx@gmail.com>
* @version  0.1
*/
class Event extends ProtoClass 
{
    const SQL_GET_IMAGES = 'SELECT * FROM `%s` WHERE `%s` = :%s';

    protected $c_type = 'event';

    public $_table = 'tblEvent';

    protected $imagesTable = 'tblEventImages';
    protected $imagesFk = 'event_id';
    protected $foreignKey = 'event_id';
    protected $imagesDir = '/img/event/';

    protected $id;

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('rewrite', 'text', true, true);
        $this->addField('descr', 'textarea');
        $this->addField('status', 'cmb', true);
        $this->addField('cdate', 'time');

        $this->addField('h1', 'text');
        $this->addField('h2', 'text');
        $this->addField('meta_keywords', 'text');
        $this->addField('meta_description', 'text');
        $this->addField('title', 'text');


        if (is_numeric($id)) {
            parent::__construct($id);
        } elseif ($id !== '') {
            $rewrite = $id;
            $arr = self::loadFromRewrite($rewrite);
            if (isset($arr['id'])) {
                parent::__construct($arr['id']);
            }
        }

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

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    public function getAll()
    {
        $sql = "SELECT `e`.* FROM `tblEvent` `e` INNER JOIN `tblEventImages` `ei` ON `ei`.`event_id` = `e`.`id` WHERE `e`.`status` = 1 GROUP BY `e`.`id` ORDER BY `e`.`id` DESC";

        $result = Database::selectQuery($sql);

        return $result;
    }

    public function loadById($event_id)
    {
        $sql = "SELECT * FROM `tblEvent` `e` INNER JOIN `tblEventImages` `ei` ON `ei`.`event_id` = `e`.`id` WHERE `e`.`id` = :id AND `e`.`status` = 1 LIMIT 1";

        $placeholders = array(':id' => $event_id);
        $result = Database::selectQuery($sql, $placeholders, true);
        $this->id = $result['id'];

        $result['images'] = $this->getPhotos($result['id']);

        $result['comments'] = $this->loadComments($result['id']);
        $result['comment_count'] = $this->countComments($result['id']);
        $result['cmntpages'] = ceil($result['comment_count']/10);

        return $result;
    }

    public function getByRewrite($rewrite)
    {
        $sql = "SELECT `e`.*, `ei`.`fname` FROM `tblEvent` `e` INNER JOIN `tblEventImages` `ei` ON `ei`.`event_id` = `e`.`id` WHERE `e`.`rewrite` = :rewrite AND `e`.`status` = 1 LIMIT 1";

        $placeholders = array(':rewrite' => $rewrite);
        $result = Database::selectQuery($sql, $placeholders, true);

        if (!$result) {
            return null;
        }
        
        $this->id = $result['id'];
        
        $result['images'] = $this->getPhotos($result['id']);

        $result['comments'] = $this->loadComments($result['id']);
        $result['comment_count'] = $this->countComments($result['id']);
        $result['cmntpages'] = ceil($result['comment_count']/10);

        return $result;
        
    }

    public function getId()
    {
        return $this->id;
    }
    public function getPhotos($id)
    {
        if (!$id) {
            return null;
        }

        $placeholders = array(':'.$this->imagesFk => $id);
        $sql = sprintf(static::SQL_GET_IMAGES, $this->imagesTable, $this->imagesFk, $this->imagesFk);
        $rows = Database::selectQuery($sql, $placeholders);

        return $rows;
    }

    public function saveComment($comment, $name, $fid, $ip, $type = 'event')
    {
        return \wtsd\misc\Comment::save($comment, $name, $fid, $ip, 'event');
    }

    public function countComments($fid, $type = 'event')
    {
        return \wtsd\misc\Comment::count($fid, 'event');
    }

    public function loadComments($fid, $count = 10, $page = 1, $type = 'event')
    {
        return \wtsd\misc\Comment::load($fid, 'event', $count, $page);   
    }

}