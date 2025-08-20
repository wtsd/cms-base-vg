<?php
namespace wtsd\geo;

use wtsd\common\Database;
/**
* Used as a storage for authentication methods. Later
* may be used as the more complicated auth mechanism.
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1.1
*/
class City
{
	public static function getAll($country_id = 1)
	{
		$qB = Database::getQueryBuilder();        
		$rows = $qB->select('*')
                ->from('`tblCity`')
                ->where('`country_id` = ' . $qB->createNamedParameter($country_id))
                ->orderBy('name', 'ASC')
                ->execute()
                ->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
	}

	public static function getAllSelect($country_id = 1)
	{
		$result = [];
		$rows = self::getAll($country_id);
		foreach ($rows as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
}