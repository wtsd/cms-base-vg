<?php
namespace wtsd\cli\Scripts;
/*
    Usage: php console.php wtsd_cli_Scripts_GetRussianCities
 */
use wtsd\cli\Script;
use wtsd\common\Database;

class GetRussianCities extends Script
{
  protected $url = 'http://api.vk.com/method/database.getCities?v=5.5&country_id=%d&offset=%d&count=%d&lang=ru';
  protected $countryId = 1;

  protected function _start()
  {

    $offset = 0;
    $count = 1000;
    $parser = new \wtsd\cli\Parser();
    $rows = $parser->receivePage(sprintf($this->url, $this->countryId, $offset, $count), null, true);
    foreach ($rows->response->items as $row) {
      $this->saveToDb($row->id, $row->title);
    }
  }

  protected function saveToDb($id, $name)
  {
    $queryBuilder = Database::getQueryBuilder();
    $queryBuilder->insert('tblCity')
            ->values([
              '`id`' => $queryBuilder->createNamedParameter($id),
              '`name`' => $queryBuilder->createNamedParameter($name),
              '`country_id`' => $queryBuilder->createNamedParameter($this->countryId),
              ])
            ->execute();
  }
}