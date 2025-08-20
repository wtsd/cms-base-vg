<?php
namespace wtsd\content;

use wtsd\common;
use wtsd\common\Database;
use wtsd\content\Category;
use wtsd\common\Text;
use wtsd\common\ProtoClass;
use wtsd\common\Register;
/**
* Defines the article entity, which is the representation of the pages
* and may contain just a bunch of HTML-code or be a redirection to
* somewhere else.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.2.1
*/
class Article extends ProtoClass
{
    const SQL_ARTICLE_PAGE = 'SELECT `a`.*, `u`.`f_name` AS `username`, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite` FROM `tblArticle` `a` INNER JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` WHERE `a`.`status` = 0 AND `a`.`cdate` < Now() ORDER BY `cdate` DESC LIMIT :off, :count',
            SQL_COUNT = 'SELECT count(*) AS `cnt` FROM `tblArticle` WHERE `status` = 0',
            SQL_GET_BY_REWRITE = 'SELECT `a`.*, `u`.`f_name` AS `username`, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite` FROM `tblArticle` `a` INNER JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` WHERE `a`.`rewrite` = :rewrite LIMIT 1',
            SQL_LOAD_ARTICLE = 'SELECT `a`.*, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite`, `c`.`rewrite` AS `cat_rewrite`, `u`.`f_name` AS `username` FROM `tblArticle` `a` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` LEFT JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` WHERE `a`.`status` = 0 AND `a`.`rewrite` = :rewrite LIMIT 1',
            SQL_COUNT_IN_CAT = 'SELECT count(id) AS `cnt` FROM `tblArticle` WHERE `status` = 0 AND `cat_id` = :id',
            SQL_GET_FROM_CAT = 'SELECT * FROM `tblArticle` WHERE `status` = 0 AND `cat_id` = :cat_id ORDER BY `cdate` DESC LIMIT :offset, :portion',
            SQL_GET_LATEST = 'SELECT * FROM `tblArticle` WHERE `status` = 0 ORDER BY `cdate` DESC LIMIT :limit',
            SQL_UNIQUE_REWRITE = "SELECT * FROM `tblArticle` WHERE `rewrite` = :rewrite LIMIT 1",
            SQL_INSERT = "INSERT INTO `tblArticle` SET `name` = :name, `rewrite` = :rewrite, `cat_id` = :cat_id, `lead` = :lead, `f_text` = :f_text, `cdate` = Now(), `mdate` = :mdate, `status` = :status, `tags` = :tags, `url` = :url, `ord` = :ord, `is_commented` = :is_commented, `user_id` = :user_id, `h1` = :h1, `h2` = :h2, `meta_keywords` = :meta_keywords, `meta_description` = :meta_description, `title` = :title, `with_images` = :with_images",
            SQL_UPDATE = "UPDATE `tblArticle` SET `name` = :name, `rewrite` = :rewrite, `cat_id` = :cat_id, `lead` = :lead, `f_text` = :f_text, `mdate` = :mdate, `status` = :status, `tags` = :tags, `url` = :url, `ord` = :ord, `is_commented` = :is_commented, `h1` = :h1, `h2` = :h2, `meta_keywords` = :meta_keywords, `meta_description` = :meta_description, `title` = :title, `with_images` = :with_images, `cdate` = `cdate` WHERE `id` = :id";

    const SQL_GETBY = "SELECT `a`.*, `u`.`f_name` AS `username`, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite`, `as`.`views` FROM `tblArticle` `a` INNER JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` LEFT JOIN `tblArticleStat` `as` ON `as`.`article_id` = `a`.`id` WHERE %s LIMIT :off, :count",
            SQL_GET_BY_ID = "SELECT * FROM `tblArticle` WHERE `id` = :id",
            SQL_COUNTBY = "SELECT count(*) AS `cnt` FROM `tblArticle` `a` INNER JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` WHERE %s",
            SQL_STATS = "SELECT * FROM `tblArticleStat` WHERE `article_id` = :id",
            SQL_INC_VIEWS = "INSERT INTO `tblArticleStat` (`article_id`,`views`) VALUES (:id, 1) ON DUPLICATE KEY UPDATE `views` = `views` + 1";


    public $_table = 'tblArticle';

    protected $imagesTable = 'tblArticleImages';
    protected $imagesFk = 'art_id';
    protected $imagesDir = '/img/article/';
    protected $uploadDir = '/uploads/article/';

    protected $attachmentTable = 'tblArticleAttachment';
    protected $attachmentFk = 'art_id';

    protected $c_type = 'article';

    protected $foreignKey = 'art_id';
   
    protected $_fields = [];

    public function __construct($id = '')
    {

        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('rewrite', 'text', true, true);
        $this->addField('cat_id', 'cmb', true);
        $this->addField('lead', 'textarea', false, true);
        $this->addField('f_text', 'textarea', false);
        $this->addField('cdate', 'time', false);
        $this->addField('mdate', 'time', false, false);
        $this->addField('status', 'cmb', true);
        $this->addField('tags');
        $this->addField('url');
        $this->addField('ord');
        $this->addField('is_commented', 'checkbox', false, true, '1');
        $this->addField('with_images', 'checkbox', false, true, '1');
        $this->addField('user_id', 'none', true, false);

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
            'cat_id' => '0',
            'lead' => '',
            'f_text' => '',
            'cdate' => date('Y-m-d H:i:s'),
            'mdate' => date('Y-m-d H:i:s'),
            'status' => '0',
            'tags' => [],
            'url' => '',
            'ord' => '',
            'is_commented' => '1',
            'with_images' => '0',
            'user_id' => '0',

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    protected function _getRecords($off, $perp, $sort = null, $filter = null)
    {

        $placeholders = array(
            ':off' => array('type' => 'int', 'value' => $off),
            ':perpage' => array('type' => 'int', 'value' => $perp),
            );
        $sql_wh = '';
        if ($filter !== null) {
            $sql_wh = ' AND `a`.`name` LIKE :filter';
            $placeholders[':filter'] = array('type' => 'string', 'value' => '%'.$filter.'%');
        }     
        $sql = sprintf("SELECT `a`.*, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite` FROM `%s` `a` LEFT JOIN `tblCategory` `c` ON `a`.`cat_id` = `c`.`id` WHERE 1 %s ORDER BY `id` DESC LIMIT :off, :perpage", $this->_table, $sql_wh);
        $rows = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['images'] = $this->getPhotos($rows[$i]['id']);
        }
        return $rows;
    }

    public function save($arr)
    {

        $now = date('Y-m-d H:i:s');

        if (!isset($arr['name']) || $arr['name'] == '') {
            return array('status' => 'error', 'msg' => 'Необходимо написать название!', 'id' => 0, 'errors' => array('Необходимо заполнить название'));
        }

        if (!isset($arr['rewrite']) || $arr['rewrite'] == '') {
            return array('status' => 'error', 'msg' => 'Необходимо заполнить поле rewrite!', 'id' => 0, 'errors' => array('Необходимо заполнить rewrite'));
        }

        $placeholders = array(':rewrite' => $arr['rewrite']);
        $row = Database::selectQuery(static::SQL_UNIQUE_REWRITE, $placeholders, true);

        if ($row && ($row['id'] != $arr['id'])) {
            return array('status' => 'error', 'msg' => 'rewrite должен быть уникальным!', 'id' => 0, 'errors' => array('rewrite должен быть уникальным'));
        }

        $placeholders[':name'] = $arr['name'];
        $placeholders[':rewrite'] = $arr['rewrite'];
        $placeholders[':cat_id'] = (int)$arr['cat_id'];
        $placeholders[':lead'] = $arr['lead'];
        $placeholders[':f_text'] = $arr['f_text'];

        $placeholders[':mdate'] = $now;

        $placeholders[':status'] = $arr['status'];
        $placeholders[':tags'] = $arr['tags'];
        $placeholders[':url'] = $arr['url'];
        $placeholders[':ord'] = $arr['ord'];
        $placeholders[':is_commented'] = isset($arr['is_commented']) ? 1 : 0;

        $placeholders[':h1'] = $arr['h1'];
        $placeholders[':h2'] = $arr['h2'];
        $placeholders[':meta_keywords'] = $arr['meta_keywords'];
        $placeholders[':meta_description'] = $arr['meta_description'];
        $placeholders[':title'] = $arr['title'];
        $placeholders[':with_images'] = $arr['with_images'];

        try {
            if (isset($arr['id']) && $arr['id'] > 0) {
                    $placeholders[':id'] = $arr['id'];

                    Database::updateQuery(static::SQL_UPDATE, $placeholders);
                    $newId = $arr['id'];
            } else {
                    $user = \wtsd\common\Factory::create('User');
                    $placeholders[':user_id'] = $user->getId();
                    
                    $newId = Database::insertQuery(static::SQL_INSERT, $placeholders);
            }
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                return array('status' => 'error', 'msg' => Register::get('lang', 'recordexists'), 'id' => 0, 'errors' => []);
            }
        }

        try {
            $this->_postSave($newId, $arr);

            $labels = Register::get('lang', 'admin');
            return array('status' => 'ok', 'msg' => $labels['msgs']['all_saved'], 'id' => $newId, 'errors' => []);
        } catch (Exception $e) {
            return array('status' => 'error', 'msg' => $e->getMessage(), 'id' => 0, 'errors' => []);
        }
    }


    public function countBy($type, $id = null)
    {
        $sqlWhere = "1";


        $qB = Database::getQueryBuilder();
        $qB->select('`a`.*', '`u`.`f_name` AS `username`', '`c`.`name` AS `cat_name`', '`c`.`rewrite` AS `cat_rewrite`'/*, '`as`.`views`'*/)
            ->from('tblArticle', 'a')
            ->innerJoin('a', 'tblUser', 'u', '`u`.`id` = `a`.`user_id`')
            ->leftJoin('a', 'tblCategory', 'c', '`c`.`id` = `a`.`cat_id`')
            //->leftJoin('a', 'tblArticleStat', 'as', '`as`.`article_id` = `a`.`id`')
            ->where('`a`.`status` = 0')
            ;

        if ($type == 'tag') {
            $qB2 = Database::getQueryBuilder();
            
            $qB2->select('`at`.`article_id`')
               ->from('tblArticleTag', 'at')
               ->leftJoin('at', 'tblTag', 't', '`t`.`id` = `at`.`tag_id`')
               ->where('`t`.`name` LIKE :tag')->setParameter(':tag', "%$id%")
               ;
            $artIds = $qB2->execute()->fetchAll(\PDO::FETCH_COLUMN);

            $qB->andWhere($qB->expr()->in('a.id', $artIds));

        }

        if ($type == 'rewrite') {
            $qB->andWhere('`a`.`status` = 0')
                ->andWhere('`a`.`rewrite` = :rewrite')->setParameter(':rewrite', $id);
        }

        if ($type == 'user') {
            $qB->andWhere('`a`.`user_id` = :user_id')->setParameter(':user_id', $id);
        }

        if ($type == 'category') {
            $qB->andWhere('`a`.`cat_id` = :cat_id')->setParameter(':cat_id', $id);
        }

        if ($type == 'search') {
            $query = \wtsd\common\Request::getGet('q');
            $sqlQuery = '%' . $query . '%';

            $qB->andWhere('`a`.`name` LIKE :q1 OR `a`.`lead` LIKE :q2 OR `a`.`f_text` LIKE :q3')
            ->setParameter(':q1', $sqlQuery)
            ->setParameter(':q2', $sqlQuery)
            ->setParameter(':q3', $sqlQuery)
            ;
        }

        if ($type == 'related') {
            $relatedIds = array_map('intval', $this->getRelated($id, 'article'));
            if (count($relatedIds) == 0) {
                return null;
            }
            $qB->andWhere(
                $qB->expr()->in(
                    $relatedIds
                    )
                );

        }

        if ($type == 'id') {
            $qB->andWhere('`a`.`id` = :id')->setParameter(':id', $id);
            $single = true;
        }

        return $qB->execute()->rowCount();
    }

    public function getBy($type, $id = null, $page = 1, $count = 10, $isDebug = false)
    {
        $single = false;

        $qB = Database::getQueryBuilder();
        $qB->select('`a`.*', '`u`.`f_name` AS `username`', '`c`.`name` AS `cat_name`', '`c`.`rewrite` AS `cat_rewrite`', '`as`.`views`')
            ->from('tblArticle', 'a')
            ->innerJoin('a', 'tblUser', 'u', '`u`.`id` = `a`.`user_id`')
            ->leftJoin('a', 'tblCategory', 'c', '`c`.`id` = `a`.`cat_id`')
            ->leftJoin('a', 'tblArticleStat', '`as`', '`as`.`article_id` = `a`.`id`')
                ->where('`a`.`status` = 0')
            ->setFirstResult(($page - 1) * $count)
            ->setMaxResults($count)
            ;

        if ($type == 'tag') {
            $qB2 = Database::getQueryBuilder();
            
            $qB2->select('`at`.`article_id`')
               ->from('tblArticleTag', 'at')
               ->leftJoin('at', 'tblTag', 't', '`t`.`id` = `at`.`tag_id`')
               ->where('`t`.`name` LIKE :tag')->setParameter(':tag', "%$id%")
               ;
            $artIds = $qB2->execute()->fetchAll(\PDO::FETCH_COLUMN);

            $qB->andWhere($qB->expr()->in('a.id', $artIds));

        }

        if ($type == 'rewrite') {
            $qB->andWhere('`a`.`status` = 0')
                ->andWhere('`a`.`rewrite` = :rewrite')->setParameter(':rewrite', $id);
        }

        if ($type == 'user') {
            $qB->andWhere('`a`.`user_id` = :user_id')->setParameter(':user_id', $id);
        }

        if ($type == 'category') {
            $qB->andWhere('`a`.`cat_id` = :cat_id')->setParameter(':cat_id', $id);
        }

        if ($type == 'top') {
            $qB->orderBy('a.cdate', 'DESC');
        }

        if ($type == 'popular') {
            $qB->orderBy('as.views', 'DESC');
        }

        if ($type == 'search') {
            $query = \wtsd\common\Request::getGet('q');
            $sqlQuery = '%' . $query . '%';

            $qB->andWhere('`a`.`name` LIKE :q1 OR `a`.`lead` LIKE :q2 OR `a`.`f_text` LIKE :q3')
            ->setParameter(':q1', $sqlQuery)
            ->setParameter(':q2', $sqlQuery)
            ->setParameter(':q3', $sqlQuery)
            ;
        }

        if ($type == 'related') {
            $relatedIds = array_map('intval', $this->getRelated($id, 'article'));
            if (count($relatedIds) == 0) {
                return null;
            }
            $qB->andWhere(
                $qB->expr()->in(
                    $relatedIds
                    )
                );

        }

        if ($type == 'id') {
            $qB->andWhere('`a`.`id` = :id')->setParameter(':id', $id);
            $single = true;
        }

        $rows = $qB->execute()->fetchAll(\PDO::FETCH_ASSOC);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['tags'] = array_map('trim', explode(',', $rows[$i]['tags']));
            if (count($rows[$i]['tags']) == 0) {
                $rows[$i]['tags'] = [];
            }

            $rows[$i]['stats'] = $this->getStats($rows[$i]['id']);

            $rows[$i]['photos'] = $this->getPhotos($rows[$i]['id']);
            $rows[$i]['attachments'] = $this->getAttachments($rows[$i]['id']);

            $rows[$i]['comments'] = $this->loadComments($rows[$i]['id']);
            $rows[$i]['comment_count'] = $this->countComments($rows[$i]['id']);
            $rows[$i]['cmntpages'] = ceil($rows[$i]['comment_count']/10);

            $rows[$i]['related'] = $this->loadRelated($rows[$i]['id']);

        }

        return $rows;
    }

    public function loadRelated($id)
    {
        return $this->getBy('related', $id);
    }

    public function getPopular($page = 1, $count = 10)
    {
        return $this->getBy('popular', '', $page, $count);
    }

    public function getById($id)
    {
        $rows = $this->getBy('id', $id);
        if (count($rows) == 1) {
            return $rows[0];
        } else {
            return $this->getEmpty();
        }
    }

    public function getByTag($tag, $page = 1, $count = 10)
    {
        return $this->getBy('tag', $tag, $page, $count);
    }

    public function getByCategory($cat_id, $page = 0, $portion = 10)
    {
        return $this->getBy('category', $cat_id, $page, $portion);
    }

    public function getLatest($limit = 5)
    {
        return $this->getBy('top', null, 1, $limit);
    }

    public function getContents($rewrite = '', $isDebug = false)
    {
        $articles = $this->getBy('rewrite', $rewrite, 1, 1, $isDebug);
        if (count($articles) > 0) {
            return $articles[0];
        }
        return null;
    }

    public function getTopArticles($page = 1, $count = 10) 
    {
        return $this->getBy('top', null, $page, $count);
    }

    protected function getByRewrite($rewrite)
    {
        return $this->getBy('rewrite', $rewrite);
    }

    public function getByUser($user_id, $page = 1, $count = 100)
    {
        return $this->getBy('user', $user_id, $page, $count);
    }

    public function doSearch($query, $page = 1, $portion = 1000)
    {
        return $this->getBy('search', $query, $page, $portion);
    }

    // Counts
    public function getAllCount()
    {
        return $this->countBy('all');
    }
    
    public function getByTagCount($tag)
    {
        return $this->countBy('tag', $tag);
    }

    public function getByUserCount($user_id)
    {
        return $this->countBy('user', $user_id);
    }

    public function getCount($cat_id)
    {
        return $this->countBy('category', $cat_id);
    }
    
    public function getCountSearch($filter)
    {
        return $this->countBy('search', $filter);
    }


    public function getFeed() 
    {
        $config = Register::get('config');
        $rets = [];
        $articles = $this->getTopArticles();
        foreach ($articles as $article) {
            $rets[] = array(
                'title' => $article['cat_name'] . ' / ' . $article['name'],
                'link' => 'http://' . $config['base_url'] . '/article/' . $article['rewrite'] . '/',
                'lead' => Text::prepare($article['lead']),
                'cdate' => $article['cdate'],
                'mdate' => $article['mdate'],
                'guid' => 'http://' . $config['base_url'] . '/article/' . $article['rewrite'] . '/'
            );
        }
        return $rets;
    }

    public function getRelated($id, $ctype = 'article')
    {
        $sql = "SELECT `obj_id_2` AS `id` FROM `tblRelated` `r` WHERE `r`.`obj_id_1` = :id AND `r`.`ctype` = :ctype";
        $placeholders = array(':id' => $id, ':ctype' => $ctype);
        $rows = Database::selectQuery($sql, $placeholders);
        if (!$rows) {
            return [];
        }
        
        $result = [];
        foreach ($rows as $row) {
            $result[] = $row['id'];
        }

        return $result;
    }

    public static function getStats($id)
    {
        $placeholders = array(':id' => $id);
        return Database::selectQuery(self::SQL_STATS, $placeholders, true);
    }


    public function addViews($id)
    {
        Database::insertQuery(self::SQL_INC_VIEWS, array(':id' => $id));
    }


    public function lst($page = 1, $filter = null)
    {
        $pages = 0;
        $cnt = ($filter === null) ? $this->getAllCount() : $this->getCountSearch($filter);

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

    protected function _postSave($id, $arr = '', $isInserted = true)
    {
        $this->uploadImages($id);
        $this->saveTags($id, $arr['tags']);
        $this->uploadAttachments($id, $arr);
        if (isset($arr['related'])) {
            $this->saveRelated($id, $arr['related']);
        }
    }

    public function saveTags($id, $tagsText)
    {
        $tags = array_map('trim', explode(',', $tagsText));

        foreach ($tags as $tag) {
            $inserts = [];
            $sql = "SELECT * FROM `tblTag` WHERE `name` = :tag";
            $placeholders = array(':tag' => $tag);
            $row = Database::selectQuery($sql, $placeholders, true);
            if (isset($row['id'])) {
                $tag_id = $row['id'];
            } else {
                $sqlInsert = "INSERT INTO `tblTag` SET `name` = :tag, `cdate` = Now()";
                $tag_id = Database::insertQuery($sqlInsert, $placeholders);
            }

            $inserts[] = '(' . intval($id) . ',' . intval($tag_id) . ')';
        }
        $sqlDel = "DELETE FROM `tblArticleTag` WHERE `article_id` = :art_id";
        $placeholdersDel = array(':art_id' => $id);
        Database::deleteQuery($sqlDel, $placeholdersDel);

        $sql = "INSERT INTO `tblArticleTag` (`article_id`, `tag_id`) VALUES " . implode(',', $inserts);
        Database::insertQuery($sql);
    }

    public function saveRelated($id, $related, $ctype = 'article')
    {
        $sqlDel = "DELETE FROM `tblRelated` WHERE `obj_id_1` = :id AND `ctype` = :ctype";
        Database::deleteQuery($sqlDel, array(':id' => $id, ':ctype' => $ctype));

        $values = [];
        $placeholders = [];
        for ($i = 0; $i < count($related); $i++) {
            $values[] = " (:obj_id_1_" . $i . ", :obj_id_2_" . $i . ", :ctype_" . $i . ") ";

            $placeholders[':obj_id_1_' . $i] = $id;
            $placeholders[':obj_id_2_' . $i] = $related[$i];
            $placeholders[':ctype_' . $i] = $ctype;
        }

        $sql = "INSERT INTO `tblRelated` (`obj_id_1`, `obj_id_2`, `ctype`) VALUES " . implode(',', $values);
        Database::insertQuery($sql, $placeholders);
    }

    public function getPrev($rewrite)
    {
        $cur = $this->getByRewrite($rewrite);
        if (isset($cur['cdate'])) {
            $sql = "SELECT * FROM `tblArticle` `a` WHERE `a`.`cdate` > :cdate AND `a`.`cat_id` = :cat_id ORDER BY `a`.`cdate`, `a`.`id` DESC LIMIT 1";
            $placeholders = array(':cdate' => $cur['cdate'], ':cat_id' => $cur['cat_id']);
            return Database::selectQuery($sql, $placeholders, true);
        }
        return [];
    }

    public function getNext($rewrite)
    {
        $cur = $this->getByRewrite($rewrite);
        if (isset($cur['cdate'])) {
            $sql = "SELECT * FROM `tblArticle` `a` WHERE `a`.`cdate` < :cdate AND `a`.`cat_id` = :cat_id ORDER BY `a`.`cdate` DESC, `a`.`id` ASC LIMIT 1";
            $placeholders = array(':cdate' => $cur['cdate'], ':cat_id' => $cur['cat_id']);
            return Database::selectQuery($sql, $placeholders, true);
        }
        return [];
    }


    public function saveComment($comment, $name, $fid, $ip, $type = 'offer')
    {
        return \wtsd\misc\Comment::save($comment, $name, $fid, $ip, 'article');
    }

    public function countComments($fid, $type = 'offer')
    {
        return \wtsd\misc\Comment::count($fid, 'article');
    }

    public function loadComments($fid, $count = 10, $page = 1, $type = 'offer')
    {
        return \wtsd\misc\Comment::load($fid, 'article', $count, $page);   
    }
/*
    public function getContents($rewrite = '')
    {
        try {

            $placeholders = array(':rewrite' => $rewrite);
            $article = Database::selectQuery(self::SQL_LOAD_ARTICLE, $placeholders, true);

            if ($article) {
                $this->id = $article['id'];
                $article['cdate_rus'] = Text::rusDate($article['cdate']);
                $article['photos'] = $this->getPhotos($article['id']);
                $article['attachments'] = $this->getAttachments($article['id']);
                $article['tags'] = array_map('trim', explode(',', $article['tags']));
                if (count($article['tags']) == 1) {
                    $article['tags'] = [];
                }
                $article['related'] = $this->loadRelated($article['id']);;
                $article['stats'] = $this->getStats($article['id']);

                $article['comments'] = $this->loadComments($article['id']);
                $article['comment_count'] = $this->countComments($article['id']);
                $article['cmntpages'] = ceil($article['comment_count']/10);

                return $article;
            } else {
                throw new \Exception('Not found!');
            }
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
    public function getByTag($tag, $page = 1, $count = 10)
    {

        $sqlQuery = '%' . $tag . '%';
        $sql = "SELECT `a`.*, `u`.`f_name` AS `username`, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite` FROM `tblArticle` `a` INNER JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` WHERE `a`.`tags` LIKE :query LIMIT :off, :count";
        $placeholders = array(
            ':query' => array('type' => 'text', 'value' => $sqlQuery),
            ':off' => array('type' => 'int', 'value' => (($page - 1) * $count)),
            ':count' => array('type' => 'int', 'value' => $count),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['tags'] = array_map('trim', explode(',', $rows[$i]['tags']));
            $rows[$i]['stats'] = $this->getStats($rows[$i]['id']);

            $rows[$i]['photos'] = $this->getPhotos($rows[$i]['id']);

            $rows[$i]['comments'] = $this->loadComments($rows[$i]['id']);
            $rows[$i]['comment_count'] = $this->countComments($rows[$i]['id']);
            $rows[$i]['cmntpages'] = ceil($rows[$i]['comment_count']/10);


        }

        return $rows;
    }

    public function getByTagCount($tag)
    {
        $sqlQuery = '%' . $tag . '%';
        $sql = "SELECT count(*) AS `cnt` FROM `tblArticle` WHERE `tags` LIKE :query";
        $placeholders = array(
            ':query' => array('type' => 'text', 'value' => $sqlQuery),
            );
        $row = Database::selectQueryBind($sql, $placeholders, true);
        return $row['cnt'];
    }



    public function getByUser($user_id, $page = 1, $count = 10)
    {

        $sql = "SELECT `a`.*, `u`.`f_name` AS `username`, `c`.`name` AS `cat_name`, `c`.`rewrite` AS `cat_rewrite` FROM `tblArticle` `a` INNER JOIN `tblUser` `u` ON `u`.`id` = `a`.`user_id` LEFT JOIN `tblCategory` `c` ON `c`.`id` = `a`.`cat_id` WHERE `a`.`user_id` = :user_id LIMIT :off, :count";
        $placeholders = array(
            ':user_id' => array('type' => 'int', 'value' => $user_id),
            ':off' => array('type' => 'int', 'value' => (($page - 1) * $count)),
            ':count' => array('type' => 'int', 'value' => $count),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['tags'] = array_map('trim', explode(',', $rows[$i]['tags']));
            $rows[$i]['stats'] = $this->getStats($rows[$i]['id']);

            $rows[$i]['comments'] = $this->loadComments($rows[$i]['id']);
            $rows[$i]['comment_count'] = $this->countComments($rows[$i]['id']);
            $rows[$i]['cmntpages'] = ceil($rows[$i]['comment_count']/10);


        }

        return $rows;
    }

    public function getByUserCount($user_id)
    {
        $sql = "SELECT count(*) AS `cnt` FROM `tblArticle` WHERE `user_id` LIKE :user_id";
        $placeholders = array(
            ':user_id' => array('type' => 'int', 'value' => $user_id),
            );
        $row = Database::selectQueryBind($sql, $placeholders, true);
        return $row['cnt'];
    }


    static public function getCount($cat_id)
    {
        $placeholders = array(':id' => $cat_id);
        $obj = Database::selectQuery(self::SQL_COUNT_IN_CAT, $placeholders, true);

        if ($obj) {
            return $obj['cnt'];
        } else {
            return 0;
        }
    }

    static public function getByCategory($cat_id, $page = 1, $portion = 10)
    {
        $offset = ($page -1) * $portion;

        $placeholders = array(
            ':cat_id' => array('type' => 'int', 'value' => $cat_id),
            ':offset' => array('type' => 'int', 'value' => $offset),
            ':portion' => array('type' => 'int', 'value' => $portion),
            );
        $objs = Database::selectQueryBind(self::SQL_GET_FROM_CAT, $placeholders);
        for ($i = 0; $i < count($objs); $i++) {
            
        }

        $result = [];
        $article = new self();
        foreach ($objs as $obj) {
            $result[] = $article->getContents($obj['rewrite']);
        }

        return $result;
        
    }

    public function getLatest($limit = 5)
    {
        $placeholders = array(
            ':limit' => array('type' => 'int', 'value' => $limit),
            );
        $arr = Database::selectQueryBind(self::SQL_GET_LATEST, $placeholders);

        for ($i = 0; $i < count($arr); $i++) {
            $arr[$i]['rus_date'] = Text::rusDate($arr[$i]['cdate']);
            $arr[$i]['photos'] = $this->getPhotos($arr[$i]['id']); 
        }

        return $arr;
    }

    static public function doSearch($query, $off = 1)
    {
        $sqlQuery = '%' . $query . '%';
        $sql = "SELECT * FROM `tblArticle` WHERE `name` LIKE :query1 OR `lead` LIKE :query2 OR `f_text` LIKE :query3";
        $placeholders = array(
            ':query1' => array('type' => 'text', 'value' => $sqlQuery),
            ':query2' => array('type' => 'text', 'value' => $sqlQuery),
            ':query3' => array('type' => 'text', 'value' => $sqlQuery),
            //':limit' => array('type' => 'int', 'value' => $limit),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows;
    }

    public function loadRelated($id)
    {
        $relatedIds = array_map('intval', $this->getRelated($id, 'article'));
        if ($relatedIds) {
            $sql = "SELECT * FROM `tblArticle` `a` WHERE `a`.`id` IN (" . implode(',', $relatedIds) .")";
            return Database::selectQuery($sql);
        } else {
            return null;
        }

    }

    */
}
