<?php
namespace wtsd\content;

use wtsd\common;
use wtsd\common\Database;
use wtsd\content\Article;
use wtsd\content\Gallery;
use wtsd\common\Text;
use wtsd\common\ProtoClass;
use wtsd\common\Register;
/**
* Defines the category entity, which is the main block of the site
* and may contain other categories or articles (pages).
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.5
*/
class Category extends ProtoClass
{

    const SQL_LOAD_SUBCATS = 'SELECT * FROM `tblCategory` WHERE `cat_id` = :id',
            SQL_LOAD_ARTICLES = 'SELECT * FROM `tblArticle` WHERE `cat_id` = :id',
            SQL_GET_BY_REWRITE = 'SELECT * FROM `tblCategory` WHERE `rewrite` = :rewrite LIMIT 1',
            SQL_LOAD_BY_ID = 'SELECT * FROM `tblCategory` WHERE id = :id LIMIT 1',
            SQL_LOAD_MENU = 'SELECT * FROM `tblCategory` WHERE status = 0 AND cat_id = 0 ORDER BY ord',
            SQL_LOAD_SUBCATS_ORD = 'SELECT * FROM `tblCategory` WHERE `cat_id` = :cat_id ORDER BY `ord`',
            SQL_LOAD_ADJCATS_ORD = 'SELECT * FROM `tblCategory` WHERE `cat_id` = :cat_id ORDER BY `ord`',
            SQL_FE_UPDATE = 'UPDATE `tblCategory` SET %s WHERE `id` = :id LIMIT 1';

    public $subcats = [];
    public $articles = [];

    public $_table = 'tblCategory';
    protected $c_type = 'category';

    function __construct($id = '')
    {
        $this->addField('id', 'none', false, false);
        $this->addField('name', 'text', true, true);
        $this->addField('rewrite', 'text', true, true);
        $this->addField('lead', 'textarea');
        $this->addField('f_text', 'textarea', false);
        $this->addField('cdate', 'time', false);
        $this->addField('mdate', 'time', false, false);
        $this->addField('status', 'cmb', true);
        $this->addField('cat_id', 'cmb', true);
        $this->addField('tags'); 
        $this->addField('url');
        $this->addField('ord');
        $this->addField('gallery_id', 'cmb', true, true);

        $this->addField('h1');
        $this->addField('h2');
        $this->addField('meta_keywords');
        $this->addField('meta_description');
        $this->addField('title');
        
        //parent::__construct($id);

        if (is_numeric($id)) {
            parent::__construct($id);
        
            $placeholders = array(':id' => $id);
            $categories = Database::selectQuery(self::SQL_LOAD_SUBCATS, $placeholders);
            $articles = Database::selectQuery(self::SQL_LOAD_ARTICLES, $placeholders);

            foreach ($categories as $category) {
                $this->subcats[] = new self($category['id']);
            }
            foreach ($articles as $article) {
                $this->articles[] = new Article($article['id']);
            }
        } elseif (strlen($id) > 0) {
            $rewrite = $id;
            $placeholders = array(':rewrite' => $rewrite);
            $cat = Database::selectQuery(self::SQL_GET_BY_REWRITE, $placeholders, true);
            self::__construct($cat['id']);
        }
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
            'rewrite' => '',
            'lead' => '',
            'f_text' => '',
            'cdate' => date('Y-m-d H:i:s'),
            'mdate' => date('Y-m-d H:i:s'),
            'status' => '0',
            'cat_id' => '0',
            'tags' => '',
            'url' => '',
            'ord' => '',
            'gallery_id' => '0',

            'h1' => '',
            'h2' => '',
            'meta_keywords' => '',
            'meta_description' => '',
            'title' => '',
            );
    }

    static public function getSubcats($id)
    {
        $subcats = [];
        $placeholders = array(':id' => $id);
        $subResult = Database::selectQuery(self::SQL_LOAD_SUBCATS, $placeholders);

        foreach ($subResult as $subrow) {
            if (trim($subrow['url']) == '') {
                $subrow['url'] = sprintf('/category/%s/', htmlspecialchars($subrow['rewrite'], ENT_QUOTES, 'utf-8'));
            }
            $subrow['sub'] = self::getSubcats($subrow['id']);
            $subcats[] = $subrow;
        }
        return $subcats;
    }

    static public function getMenu()
    {
        $returns = [];

        $results = Database::selectQuery(self::SQL_LOAD_MENU);

        if (count($results) > 0) {
            foreach ($results as $row) {
                if (trim($row['url']) == '') {
                    $row['url'] = sprintf('/category/%s/', htmlspecialchars($row['rewrite'], ENT_QUOTES, 'utf-8'));
                }
                $row['sub'] = self::getSubcats($row['id']);
                
                $returns[] = $row;
            }
        }
        return $returns;
    }

    public function getBreadcrumb($id)
    {
        // @todo: Get cat_id of id till there is 0 in cat_id
        $placeholders = array(':id' => $id);
        $obj = Database::selectQuery(self::SQL_LOAD_BY_ID, $placeholders, true);
        $path = [];
        //$path[] = $obj;
        if (intval($obj['cat_id']) > 0) {
            $placeholders = array(':id' => $obj['cat_id']);
            $parent = Database::selectQuery(self::SQL_LOAD_BY_ID, $placeholders, true);
            if (intval($parent['cat_id']) > 0) {
                $placeholders = array(':id' => $parent['cat_id']);
                $path[] = Database::selectQuery(self::SQL_LOAD_BY_ID, $placeholders, true);
            }
            $path[] = $parent;
        }
        return $path;
    }

    public function buildContents($rewrite = '', $page = 1, $portion = 5)
    {

        try {

            if (mb_strlen($rewrite) === 0) {
                return null;
            }
            
            $placeholders = array(':rewrite' => $rewrite);
            $row = Database::selectQuery(self::SQL_GET_BY_REWRITE, $placeholders, true);
            if (!$row) {
                throw new \Exception('Not found!');
            }
            // Title
            if (mb_strlen($row['title']) > 0) {
                $title = Text::prepare($row['title']);
            } elseif (trim($row['name']) != '') {
                $title = sprintf('%s — %s', Text::prepare($row['name']), Register::get('lang', 'global_title'));
            } else {
                $title = Register::get('lang', 'global_title');
            }

            $values = [];
            if ($row !== null) {

                $article = new Article();
                $values['row'] = $row;
                $values['row']['is_img'] = false;
                $values['row']['subcats'] = $this->buildSubcats($row['id']);
                $values['row']['adjcats'] = $this->buildSubcats($row['cat_id']);
                $values['row']['articles'] = $article->getByCategory($row['id'], $page, $portion);

                if (Register::get('config', 'with_news')) {
                    $values['row']['news'] = $article->getLatest(Register::get('config', 'news_cat'), Register::get('config', 'news_count'));
                }

                $values['row']['breadcrumb'] = $this->getBreadcrumb($row['id']);


                $values['page_title'] = $title;

                $values['curPage'] = $page;
                $values['pages'] = floor($article->getCount($row['id']) / $portion);
                $values['preUrl'] = sprintf('/category/%s/', $row['rewrite']);

                if ($row['gallery_id'] > 0) {
                    $gal = new Gallery($row['gallery_id']);
                    $gallery_arr = $gal->buildMain();
                    $values = array_merge($values, $gallery_arr);
                }

            }
            $art = new Article();
            
            $values['news'] = $art->getTopArticles(Register::get('config', 'news_count'));
            $values['articlesblock'] = $art->getTopArticles();

            return $values;
        } catch (\Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    static public function buildAdjcats($id)
    {
        $catId = $this->_cat_id;
        $placeholders = array(':cat_id' => intval($catId));
        $objs = Database::selectQuery(self::SQL_LOAD_ADJCATS_ORD, $placeholders);

        $subcats = [];
        if (count($objs) > 0) {
            foreach ($objs as $row) {
                $subcats[] = array(
                    'url' => ($row['url'] != '') ? $row['url'] : '/category/' . $row['rewrite'] . '/',
                    'name' => htmlspecialchars($row['name'], ENT_QUOTES, 'utf-8')
                );
            }
        }
        return $subcats;
    }
    
    static public function buildSubcats($id = 0)
    {
        $placeholders = array(':cat_id' => intval($id));
        $objs = Database::selectQuery(self::SQL_LOAD_SUBCATS_ORD, $placeholders);

        $subcats = [];
        if (count($objs) > 0) {
            foreach ($objs as $row) {
                $subcats[] = array(
                    'url' => ($row['url'] != '') ? $row['url'] : '/category/' . $row['rewrite'] . '/',
                    'name' => htmlspecialchars($row['name'], ENT_QUOTES, 'utf-8')
                );
            }
        }
        return $subcats;
    }

    /**
     * For admin's panel tree combo input contents generation.
     * 
     */
    public function getCatsHierarchy($cat_id = 0, $level = 0)
    {

        $prefix = '';
        for ($i = 0; $i < $level; $i++) {
            $prefix .= '--';
        }

        $arr = [];
        if ($level == 0) {
            $arr[] = array('id' => 0, 'name' => '-- --');
        }

        $placeholders = array(':cat_id' => $cat_id);
        $objs = Database::selectQuery(self::SQL_LOAD_SUBCATS_ORD, $placeholders);
        foreach ($objs as $row) {
            $arr[] = array('id' => $row['id'], 'name' => $prefix . ' ' . $row['name']);
            $tmp_arr = $this->getCatsHierarchy($row['id'], ($level + 1));
            $arr = array_merge($arr, $tmp_arr);
        }
        return $arr;
    }

    static public function combo($id)
    {
        return array(
            'options' => self::getCatsHierarchy(),
            'value' => $id
            );
    }
    
    static public function idToName($id)
    {
        $placeholders = array(':id' => intval($id));
        $obj = Database::selectQuery(self::SQL_LOAD_BY_ID, $placeholders);

        if (isset($obj['name'])) {
            return htmlspecialchars($obj['name'], ENT_QUOTES, 'utf-8');
        }
    }

    protected function _getRecordsUp($parent)
    {
        $sql = "SELECT `c1`.*, `c2`.`name` AS `parent_name` FROM `tblCategory` `c1` LEFT JOIN `tblCategory` `c2` ON `c1`.`cat_id` = `c2`.`id` WHERE `c1`.`cat_id` = :parent";
        $placeholders = array(
            ':parent' => array('type' => 'int', 'value' => $parent),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        for ($i = 0; $i < count($rows); $i++) {
            $rows[$i]['subcats'] = $this->_getRecordsUp($rows[$i]['id']);
        }
        return $rows;
    }


    protected function _getCount($filter = null)
    {
        $sql_all = sprintf("SELECT count(id) AS `cnt` FROM `%s` WHERE `cat_id` = 0", $this->_table);
        $row = Database::selectQuery($sql_all, null, true);

        return $row['cnt'];
    }


    public function lst($page = 1, $filter = null)
    {
        $cnt = $this->_getCount($filter);
        $pages = 0;

        $records = [];
        if ($cnt > 0) {
            $pages = floor($cnt / $this->_perPage) + 1;

            
            $off = intval($this->_perPage * ($page - 1));
            $perp = intval($this->_perPage);

            $rows = $this->_getRecordsUp(0);
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
        $sql = "SELECT * FROM `tblCategory` WHERE `name` LIKE :query1 OR `lead` LIKE :query2 OR `f_text` LIKE :query3";
        $placeholders = array(
            ':query1' => array('type' => 'text', 'value' => $sqlQuery),
            ':query2' => array('type' => 'text', 'value' => $sqlQuery),
            ':query3' => array('type' => 'text', 'value' => $sqlQuery),
            //':limit' => array('type' => 'int', 'value' => $limit),
            );
        $rows = Database::selectQueryBind($sql, $placeholders);

        return $rows;
    }

}

