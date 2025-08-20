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
class Country
{
	public static function getAll()
	{
		$qB = Database::getQueryBuilder();        
		$rows = $qB->select('*')
                ->from('`tblCountry`')
                ->orderBy('name')
                ->execute()
                ->fetchAll(\PDO::FETCH_ASSOC);
        return $rows;
	}

	public static function getAllSelect()
	{
		$result = [];
		$rows = self::getAll();
		foreach ($rows as $row) {
			$result[$row['id']] = $row['name'];
		}
		return $result;
	}
}