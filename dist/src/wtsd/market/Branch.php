<?php
namespace wtsd\market;

use wtsd\common\Database;
use PDO;
/**
* Used as a storage for authentication methods. Later
* may be used as the more complicated auth mechanism.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1.1
*/
class Branch
{
    protected $table = 'tblBranch';

    public static $statuses = array(
        1 => 'Активный',
        0 => 'Закрыт',
        );

    public function count()
    {

        $queryBuilder = Database::getQueryBuilder();
        $row = $queryBuilder->select('count(*) AS `cnt`')
            ->from('tblBranch')
            //->where('`is_deleted` = 0')
            ->execute()
            ->fetch(\PDO::FETCH_ASSOC);
    
        return $row['cnt'];
    }

    public function getAllActive()
    {
        $queryBuilder = Database::getQueryBuilder();
        
        $rows = $queryBuilder->select('`b`.*')
            ->from('tblBranch', 'b')
            ->andWhere('`b`.`is_active` = 1')
            ->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return $rows;
    }
    public function getAll($page = 1, $size = 1000, $filter = '')
    {
        $offset = (($page - 1) * $size);

        $queryBuilder = Database::getQueryBuilder();
        
        $queryBuilder->select('`b`.*')
            ->from('tblBranch', 'b')
            //->where('`b`.`is_active` = 0')
            ->setFirstResult($offset)
            ->setMaxResults($size);

        if ($filter != '') {
            //$queryBuilder->andWhere('`e`.`name` LIKE :name')->setParameter(':name', "%$filter%");
        }

        $rows = $queryBuilder->execute()->fetchAll(\PDO::FETCH_ASSOC);

        return $rows;
    }
    
    public function load($id = null)
    {
        if (!$id && $this->id) {
            $id = $this->id;
        }
      $queryBuilder = Database::getQueryBuilder();
        
        $queryBuilder->select('`b`.*')
            ->from('tblBranch', 'b')
            ->where('`is_active` = 0')
            ->andWhere('`b`.`id` = '. $queryBuilder->createNamedParameter($id));

        $row = $queryBuilder->execute()->fetch(\PDO::FETCH_ASSOC);

        if (count($row) == 0) {
            throw new \Exception('Branch not found!');
        }

        if ($row) {
            foreach ($row as $field => $val) {
                $this->$field = $val;
            }
        }

        return $row;
    }

    public function getArray()
    {
        $result = array();
        foreach ($this as $field => $val) {
            $result[$field] = $val;
        }
        return $result;
    }

    public function getEmpty()
    {
        return array(
            'id' => '0',
            'name' => '',
			'address' => '',
			'tel' => '',
			'opens_at' => '',
			'closes_at' => '',
			'is_active' => '1',
			'is_public' => '1',
			'lng' => '',
			'lat' => '',
			'comment' => '',
			'city' => '2',
			'country' => '1',
			'zip' => '',
			'contact' => '',
            );
    }

    public static function validate($arr)
    {
        $errors = [];
        // Required

        
        // Types

        return $errors;
    }

    public function save($arr)
    {
        $placeholders = array();
        foreach ($arr as $field => $value) {
            $placeholders[':'.$field] = $value;
        }

        $placeholders = array(
        	':name' => $arr['name'],
			':address' => $arr['address'],
			':tel' => $arr['tel'],
			':opens_at' => $arr['opens_at'],
			':closes_at' => $arr['closes_at'],
			':is_active' => $arr['is_active'],
			':is_public' => $arr['is_public'],
			':lng' => $arr['lng'],
			':lat' => $arr['lat'],
			':comment' => $arr['comment'],
			':city' => $arr['city'],
			':country' => $arr['country'],
			':zip' => $arr['zip'],
			':contact' => $arr['contact'],

            );

        if ($arr['id'] > 0) {
            $this->load($arr['id']);
            $placeholders[':id'] = $arr['id'];
            $this->update($placeholders);
        }
        if ($arr['id'] == 0) {
            $this->insert($placeholders);
        }

    }

    public function update($placeholders)
    {

        $queryBuilder = Database::getQueryBuilder();        
        $queryBuilder->update('tblBranch')
            ->where('`id` = '. $queryBuilder->createNamedParameter($placeholders[':id']));


		if(isset($placeholders[':name'])) {
			$queryBuilder->set('`name`', $queryBuilder->createNamedParameter($placeholders[':name']));
		}
		if(isset($placeholders[':address'])) {
			$queryBuilder->set('`address`', $queryBuilder->createNamedParameter($placeholders[':address']));
		}
		if(isset($placeholders[':tel'])) {
			$queryBuilder->set('`tel`', $queryBuilder->createNamedParameter($placeholders[':tel']));
		}
		if(isset($placeholders[':opens_at'])) {
			$queryBuilder->set('`opens_at`', $queryBuilder->createNamedParameter($placeholders[':opens_at']));
		}
		if(isset($placeholders[':closes_at'])) {
			$queryBuilder->set('`closes_at`', $queryBuilder->createNamedParameter($placeholders[':closes_at']));
		}
		if(isset($placeholders[':is_active'])) {
			$queryBuilder->set('`is_active`', $queryBuilder->createNamedParameter($placeholders[':is_active']));
		}
		if(isset($placeholders[':is_public'])) {
			$queryBuilder->set('`is_public`', $queryBuilder->createNamedParameter($placeholders[':is_public']));
		}
		if(isset($placeholders[':lng'])) {
			$queryBuilder->set('`lng`', $queryBuilder->createNamedParameter($placeholders[':lng']));
		}
		if(isset($placeholders[':lat'])) {
			$queryBuilder->set('`lat`', $queryBuilder->createNamedParameter($placeholders[':lat']));
		}
		if(isset($placeholders[':comment'])) {
			$queryBuilder->set('`comment`', $queryBuilder->createNamedParameter($placeholders[':comment']));
		}
		if(isset($placeholders[':city'])) {
			$queryBuilder->set('`city`', $queryBuilder->createNamedParameter($placeholders[':city']));
		}
		if(isset($placeholders[':country'])) {
			$queryBuilder->set('`country`', $queryBuilder->createNamedParameter($placeholders[':country']));
		}
		if(isset($placeholders[':zip'])) {
			$queryBuilder->set('`zip`', $queryBuilder->createNamedParameter($placeholders[':zip']));
		}
		if(isset($placeholders[':contact'])) {
			$queryBuilder->set('`contact`', $queryBuilder->createNamedParameter($placeholders[':contact']));
		}

        $queryBuilder->execute();
    }


    public function insert($placeholders)
    {

        $queryBuilder = Database::getQueryBuilder();        
        $queryBuilder->insert('tblBranch')
            ->values(
                [


'`name`' => $queryBuilder->createNamedParameter($placeholders[':name']),
'`address`' => $queryBuilder->createNamedParameter($placeholders[':address']),
'`tel`' => $queryBuilder->createNamedParameter($placeholders[':tel']),
'`opens_at`' => $queryBuilder->createNamedParameter($placeholders[':opens_at']),
'`closes_at`' => $queryBuilder->createNamedParameter($placeholders[':closes_at']),
'`is_active`' => $queryBuilder->createNamedParameter($placeholders[':is_active']),
'`is_public`' => $queryBuilder->createNamedParameter($placeholders[':is_public']),
'`lng`' => $queryBuilder->createNamedParameter($placeholders[':lng']),
'`lat`' => $queryBuilder->createNamedParameter($placeholders[':lat']),
'`comment`' => $queryBuilder->createNamedParameter($placeholders[':comment']),
'`city`' => $queryBuilder->createNamedParameter($placeholders[':city']),
'`country`' => $queryBuilder->createNamedParameter($placeholders[':country']),
'`zip`' => $queryBuilder->createNamedParameter($placeholders[':zip']),
'`contact`' => $queryBuilder->createNamedParameter($placeholders[':contact']),

                ]
                )
            ;

        try {
            $queryBuilder->execute();
            return Database::lastId();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function delete($id = null)
    {
        $sql = "DELETE FROM `tblBranch` WHERE `id` = :id LIMIT 1";
        $placeholders = array(':id' => $this->id);
        if ($id !== null) {
            $placeholders = array(':id' => $id);
        }
        $result = Database::updateQuery($sql, $placeholders);

        return $result;
    }

    public function toggleStatus()
    {
        $sql = "UPDATE `tblBranch` SET `is_active` = :is_active WHERE `id` = :id";
        $placeholders = array(':id' => $this->id);
        if ($this->is_active == 1) {
            $placeholders[':is_active'] = 0;
        } else {
            $placeholders[':is_active'] = 1;
        }
        $result = Database::updateQuery($sql, $placeholders);

        return $result;
    }

    public function getStatuses()
    {
        return self::$statuses;
    }
}