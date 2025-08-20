<?php
namespace wtsd\misc;

use wtsd\common\Database;

class Comment
{
    const SQL_ADD = 'INSERT INTO `tblComments` SET `comment` = :comment, `name` = :name, `fid` = :fid, `ip` = :ip, `type` = :type, `cdate` = Now()',
        SQL_COUNT = 'SELECT count(*) AS `cnt` FROM `tblComments` WHERE `fid` = :fid AND `type` = :type AND `status` = 1',
        SQL_LOAD = 'SELECT * FROM `tblComments` WHERE `fid` = :fid AND `type` = :type AND `status` = 1 ORDER BY `cdate` DESC LIMIT :offset, :count';

    public static function checkCaptcha($gRecaptchaResponse, $ip)
    {
        $config = \wtsd\common\Register::get('config');

        $recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha']['secret']);
        $resp = $recaptcha->verify($gRecaptchaResponse, $ip);
        return $resp->isSuccess();
    }

    public static function save($comment, $name, $fid, $ip, $type, $user_id = 0)
    {
        $placeholders = array(':comment' => $comment, ':name' => $name, ':ip' => $ip, ':fid' => $fid, ':type' => $type);
        $newId = Database::insertQuery(self::SQL_ADD, $placeholders);

        return $newId;
    }

    public static function count($fid, $type)
    {
        $placeholders = array(':fid' => $fid, ':type' => $type);
        $cnt = Database::selectQuery(self::SQL_COUNT, $placeholders, true)['cnt'];
        return $cnt;
    }

    public static function load($fid, $type, $count = 10, $page = 1)
    {
        $offset = ($page - 1) * 10;
        $placeholders = array(
            ':offset' => array('type' => 'int', 'value' => $offset),
            ':count' => array('type' => 'int', 'value' => $count),
            ':fid' => array('type' => 'int', 'value' => $fid),
            ':type' => array('type' => 'string', 'value' => $type),
            );
        try {
            $rows = Database::selectQueryBind(self::SQL_LOAD, $placeholders);
        } catch (\Exception $e) {
            return [];
        }
        return $rows;
    }

    public static function renderPage($type, $fid, $page, $portion = 10)
    {
        $view = new \wtsd\common\Template('default');
        $view->assignAll(array(
            'comments' => self::load($fid, $type, $portion, $page),
            'pages' => ceil(self::count($fid, $type)/$portion),
            'page' => $page,
            'type' => $type,
            'fid' => $fid,
            ));
        return $view->render('includes/comments.tpl');
    }
}