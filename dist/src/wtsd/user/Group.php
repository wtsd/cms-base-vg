<?php
namespace wtsd\user;

use wtsd\common\Database;
use PDO;
/**
* Used as a storage for user groups.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1.1
*/
class Group
{
    protected $id = 0;
    protected $table = 'tblGroup';

    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function count()
    {
        $sql = sprintf("SELECT count(*) AS `cnt` FROM `tblGroup`");
        $row = Database::selectQuery($sql, null, true);

        return $row['cnt'];
    }

    public function getAll()
    {
        
        $sql = "SELECT * FROM `tblGroup` ORDER BY `name`";
        $rows = Database::selectQuery($sql);
        return $rows;
    }

    public function getAllSelect()
    {
        $rows = $this->getAll();
        $result = [];
        for ($i = 0; $i < count($rows); $i++) {
            $result[$rows[$i]['id']] = $rows[$i]['name'];
        }
        return $result;
    }

    public function load($id)
    {
        $sql = "SELECT * FROM `tblGroup` WHERE `id` = :id";
        $placeholders = [':id' => $id];
        $row = Database::selectQuery($sql, $placeholders, true);

        if (count($row) == 0) {
            throw new \Exception('Group is not found!');
        }

        if (is_array($row)) {
            foreach ($row as $field => $val) {
                $this->$field = $val;
            }
        }

        return $row;
    }

    public function getArray()
    {
        $result = [];
        foreach ($this as $field => $val) {
            $result[$field] = $val;
        }
        return $result;
    }

    public function getEmpty()
    {
        return [
            'id' => '0',
            'name' => '',
            'comment' => '',
            'status' => '1',
            ];
    }

    public static function validate($arr)
    {
        $errors = [];
        // Required
        if (!isset($arr['id'])) {
            if (!isset($arr['name'])) {
                $errors[] = 'Name is required';
            }
        }

        return $errors;
    }

    public function save($arr)
    {
        $placeholders = [
            ':name' => $arr['name'],
            ':comment' => $arr['comment'],
            ':status' => $arr['status'],
                ];
        if ($arr['id'] > 0) {
            $this->load($arr['id']);
            $placeholders[':id'] = $arr['id'];
            $this->update($placeholders);
            return $arr['id'];
        }

        if ($arr['id'] == 0) {
        	$user = \wtsd\common\Factory::create('User');
        	$placeholders[':user_id'] = $user->getId();
            $this->insert($placeholders);
            return $this->id;
        }

    }

    public function update($placeholders)
    {
        $sql = "UPDATE `tblGroup` SET
        	`name` = :name,
        	`comment` = :comment,
        	`status` = :status
        	WHERE `id` = :id";
        Database::updateQuery($sql, $placeholders);
    }

    public function insert($placeholders)
    {
        $sql = "INSERT INTO `tblGroup` SET 
        	`name` = :name,
        	`comment` = :comment,
        	`status` = :status,
        	`user_id` = :user_id";
        $this->id = Database::insertQuery($sql, $placeholders);
    }

    public function delete()
    {
        $sql = "DELETE FROM `tblGroup` WHERE `id` = :id LIMIT 1";
        $placeholders = [':id' => $this->id];
        $result = Database::deleteQuery($sql, $placeholders);

        return $result;
    }

    public static function getNameById($id)
    {
        $sql = "SELECT * FROM `tblGroup` WHERE `id` = :id LIMIT 1";
        $placeholders = [':id' => $id];
        $result = Database:: selectQuery($sql, $placeholders, true);

        return $result['name'];
    }
}