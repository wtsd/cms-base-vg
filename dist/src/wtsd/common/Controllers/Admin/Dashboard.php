<?php
namespace wtsd\common\Controllers\Admin;

use wtsd\common;
use wtsd\common\Controller;
use wtsd\common\AdminController;
use wtsd\common\Request;
/**
* 
*
* @author    Vladislav Gafurov <warlockfx@gmail.com>
* @version    0.1
*/
class Dashboard extends AdminController
{
    protected $onlyAuthorized = true;
    protected $defaultUrl = '/dashboard/';

    public function run()
    {
        $this->template = 'dashboard.tpl';

        $this->contents = [];

        $article = new \wtsd\content\Article();
        $this->contents['posts'] = $article->getLatest(3);

        return $this->contents;
    }

    protected function chart($chartName, $subtitle = '')
    {
        /*$schedule = new \wtsd\event\Schedule();
        $records = $schedule->getAll();
        $event = new \wtsd\event\Event();
        $events = $event->getAll();

        $curMonth = date('m');
        $days = [];

        for ($i = 1; $i < date('t'); $i++) {
            $days[] = date('Y-m-'.$i);
        }
        foreach ($events as $event) {
            $data = [];
            for ($i = 1; $i < date('t'); $i++) {
                $startDate = date('Y-m-'.$i.' 00:00:00');
                $endDate = date('Y-m-'.$i.' 23:59:59');
                $cnt = $schedule->getCountByDateEventId($startDate, $endDate, $event['id']);
                $data[] = array(intval($cnt));
            }
            $series[] = array('name' => $event['name'], 'data' => $data);
        }

        return array(
            'chart' => array('type' => 'column'),
            'title' => array('text' => $chartName),
            'subtitle' => array('text' => $subtitle),
            'xAxis' => array(
                'categories' => $days,
            ),
            'yAxis' => array(
                'title' => array(
                    'text' => 'Количество',
                ),
            ),
            'series' => $series,
            );
            */
    }
}
