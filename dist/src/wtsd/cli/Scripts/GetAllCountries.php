<?php
namespace wtsd\cli\Scripts;
/*
    Usage: php console.php wtsd_cli_Scripts_GetAllCountries
 */
use wtsd\cli\Script;
use wtsd\common\Register;
use wtsd\common\Database;

class GetAllCountries extends Script
{
  protected $url = 'http://api.vk.com/method/database.getCountries?v=5.5&need_all=1&count=1000&lang=ru';

  protected function _start()
  {
    $parser = new \wtsd\cli\Parser();
    $rows = $parser->receivePage($this->url, null, true);
    foreach ($rows->response->items as $row) {
      $id = $this->saveToDb($row->id, $row->title);
      echo "New record was added.".PHP_EOL;
    }
  }

  protected function saveToDb($id, $name)
  {
    $queryBuilder = Database::getQueryBuilder();
    $queryBuilder->insert('tblCountry')
            ->values([
              '`id`' => $queryBuilder->createNamedParameter($id),
              '`name`' => $queryBuilder->createNamedParameter($name),
              ])
            ->execute();
    return $id;
  }
}